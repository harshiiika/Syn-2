// Complete Calendar Module - FIXED: Timezone & Event Loading Issues
(function() {
    'use strict';

    let calendar;
    let allEvents = [];
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Initialize everything once DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        initializeSidebar();
        initializeCalendar();
        attachEventListeners();
        autoHideFlashMessages();
    });

    /**
     * SIDEBAR TOGGLE - Handles collapsible sidebar
     */
    function initializeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const text = document.getElementById('text');
        const calendarContainer = document.querySelector('.calendar-container');

        if (!sidebar || !toggleBtn) {
            console.warn('Sidebar elements not found');
            return;
        }

        let isCollapsed = false;

        // Set initial styles
        sidebar.style.transition = 'width 0.3s ease-in-out';
        sidebar.style.width = '300px';
        sidebar.style.flexShrink = '0';

        if (calendarContainer) {
            calendarContainer.style.transition = 'flex 0.3s ease-in-out';
            calendarContainer.style.flex = '1';
        }

        // Toggle button click handler
        toggleBtn.addEventListener('click', function() {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                // COLLAPSE
                sidebar.style.width = '42px';
                if (text) text.style.display = 'none';
            } else {
                // EXPAND
                sidebar.style.width = '300px';
                if (text) text.style.display = 'block';
            }

            // Resize calendar after transition
            setTimeout(() => {
                if (calendar && typeof calendar.updateSize === 'function') {
                    calendar.updateSize();
                }
            }, 350);

            console.log('Sidebar toggled:', isCollapsed ? 'collapsed' : 'expanded');
        });
    }

    /**
     * INITIALIZE FULLCALENDAR
     */
    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl || typeof FullCalendar === 'undefined') {
            console.warn('Calendar element or FullCalendar library not found');
            return;
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: { 
                left: 'prev,next today', 
                center: 'title', 
                right: 'dayGridMonth,dayGridWeek' 
            },
            timeZone: 'local', // FIXED: Use local timezone
            events: loadEvents,
            eventClick: info => {
                if (confirm('Delete this event?')) {
                    deleteEvent(info.event);
                }
            },
            datesSet: updateSidebarForCurrentMonth,
            height: 'auto',
            themeSystem: 'standard'
        });

        calendar.render();
        
        // Expose globally for resize triggers
        window.calendar = calendar;
    }

    /**
     * NORMALIZE DATE TO LOCAL TIMEZONE (fixes date shifting)
     * Ensures dates are treated as local, not UTC
     */
    function normalizeDateString(dateStr) {
        if (!dateStr) return dateStr;
        
        // If it's already in YYYY-MM-DD format, append local time
        if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
            return dateStr + 'T00:00:00';
        }
        
        return dateStr;
    }

    /**
     * LOAD EVENTS FROM SERVER
     */
    function loadEvents(info, successCallback, failureCallback) {
        fetch('/calendar/events', { 
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'Accept': 'application/json' 
            } 
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // FIXED: Normalize all event dates and handle ID formats
                const normalizedEvents = data.data.map(event => {
                    // Handle both old and new ID formats
                    let eventId = event.id;
                    let eventType = event.type;
                    
                    // If ID has prefix format (holiday-123 or test-456), extract type and ID
                    if (typeof eventId === 'string' && eventId.includes('-')) {
                        const parts = eventId.split('-');
                        if (parts.length >= 2 && (parts[0] === 'holiday' || parts[0] === 'test')) {
                            eventType = parts[0];
                            eventId = parts.slice(1).join('-'); // Handle IDs with multiple dashes
                        }
                    }
                    
                    return {
                        ...event,
                        id: eventId,
                        type: eventType || event.type,
                        start: normalizeDateString(event.start),
                        backgroundColor: (eventType || event.type) === 'holiday' ? '#dc3545' : '#0d6efd'
                    };
                });
                
                allEvents = normalizedEvents;
                successCallback(normalizedEvents);
                updateSidebarForCurrentMonth();
                
                console.log('Loaded events:', normalizedEvents.length);
            } else {
                failureCallback(data.message || 'Failed to load events');
            }
        })
        .catch(err => {
            console.error('Load events error:', err);
            failureCallback(err);
        });
    }

    /**
     * ATTACH EVENT LISTENERS FOR BUTTONS AND FORMS
     */
    function attachEventListeners() {
        // Modal buttons
        const addHolidayBtn = document.getElementById('addHolidayBtn');
        const addTestBtn = document.getElementById('addTestBtn');

        if (addHolidayBtn) {
            addHolidayBtn.addEventListener('click', () => {
                new bootstrap.Modal(document.getElementById('addHolidayModal')).show();
            });
        }

        if (addTestBtn) {
            addTestBtn.addEventListener('click', () => {
                new bootstrap.Modal(document.getElementById('addTestModal')).show();
            });
        }

        // Form submissions
        const holidayForm = document.getElementById('holidayForm');
        if (holidayForm) {
            holidayForm.addEventListener('submit', e => handleFormSubmit(e, 'holiday'));
        }

        const testForm = document.getElementById('testForm');
        if (testForm) {
            testForm.addEventListener('submit', e => handleFormSubmit(e, 'test'));
        }

        // Mark all Sundays
        const markSundayBtn = document.getElementById('markAllSundayBtn');
        if (markSundayBtn) {
            markSundayBtn.addEventListener('click', markAllSundays);
        }
    }

    /**
     * HANDLE FORM SUBMISSION (Holiday/Test)
     */
    function handleFormSubmit(e, type) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        const data = type === 'holiday' 
            ? { 
                date: formData.get('holiday_date'), 
                description: formData.get('holiday_description') 
              }
            : { 
                date: formData.get('test_date'), 
                description: formData.get('test_name') 
              };

        const url = type === 'holiday' ? '/calendar/holidays' : '/calendar/tests';

        fetch(url, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                showNotification(`${capitalize(type)} added successfully`, 'success');
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                if (modal) modal.hide();
                form.reset();
                
                // FIXED: Add event with normalized date
                const newEvent = {
                    id: res.data?.id || `temp-${Date.now()}`,
                    title: data.description,
                    start: normalizeDateString(data.date),
                    allDay: true,
                    type: type,
                    backgroundColor: type === 'holiday' ? '#dc3545' : '#0d6efd'
                };
                
                calendar.addEvent(newEvent);
                allEvents.push(newEvent);
                updateSidebarForCurrentMonth();
            } else {
                showNotification(res.message || `Failed to add ${type}`, 'danger');
            }
        })
        .catch(err => { 
            console.error(err); 
            showNotification(`Error adding ${type}`, 'danger'); 
        });
    }

    /**
     * GET CURRENT MONTH RANGE
     */
    function getCurrentMonthRange() {
        if (!calendar) return null;
        
        const currentDate = calendar.getDate();
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        return { 
            startDate: new Date(year, month, 1),
            endDate: new Date(year, month + 1, 0),
            month, 
            year 
        };
    }

    /**
     * COMPARE DATES (ignoring time component)
     */
    function isSameDate(date1, date2) {
        const d1 = new Date(date1);
        const d2 = new Date(date2);
        
        return d1.getFullYear() === d2.getFullYear() &&
               d1.getMonth() === d2.getMonth() &&
               d1.getDate() === d2.getDate();
    }

    /**
     * CHECK IF DATE IS IN RANGE
     */
    function isDateInRange(dateStr, startDate, endDate) {
        const eventDate = new Date(dateStr);
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        // Set all times to midnight for fair comparison
        eventDate.setHours(0, 0, 0, 0);
        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);
        
        return eventDate >= start && eventDate <= end;
    }

    /**
     * UPDATE SIDEBAR LISTS FOR CURRENT MONTH
     */
    function updateSidebarForCurrentMonth() {
        const range = getCurrentMonthRange();
        if (!range) return;

        const { startDate, endDate } = range;

        // FIXED: Filter using proper date comparison
        const holidays = allEvents
            .filter(e => e.type === 'holiday' && isDateInRange(e.start, startDate, endDate))
            .sort((a, b) => new Date(a.start) - new Date(b.start));

        const tests = allEvents
            .filter(e => e.type === 'test' && isDateInRange(e.start, startDate, endDate))
            .sort((a, b) => new Date(a.start) - new Date(b.start));

        renderList('holiday', holidays);
        renderList('test', tests);
        
        console.log(`Sidebar updated: ${holidays.length} holidays, ${tests.length} tests`);
    }

    /**
     * RENDER SIDEBAR LIST
     */
    function renderList(type, items) {
        const listEl = document.getElementById(`${type}List`);
        if (!listEl) return;

        if (items.length === 0) {
            listEl.innerHTML = `<div class="list-item-empty">No ${type}s this month</div>`;
            return;
        }

        listEl.innerHTML = items.map(item => `
            <div class="list-item">
                <div>
                    <div class="list-item-date">${formatDate(item.start)}</div>
                    <div class="list-item-desc">${item.title}</div>
                </div>
                <div class="list-item-actions">
                    <button class="btn-action btn-delete" onclick="window.delete${capitalize(type)}('${item.id}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * DELETE EVENT FUNCTIONS
     */
    ['holiday', 'test'].forEach(type => {
        window['delete' + capitalize(type)] = function(id) {
            if (!confirm(`Are you sure you want to delete this ${type}?`)) return;
            
            fetch(`/calendar/${type}s/${id}`, { 
                method: 'DELETE', 
                headers: { 
                    'X-CSRF-TOKEN': CSRF_TOKEN, 
                    'Accept': 'application/json' 
                } 
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(`${capitalize(type)} deleted successfully`, 'success');
                    
                    // FIXED: Find event by ID (handle string/number comparison)
                    const event = calendar.getEventById(String(id));
                    if (event) event.remove();
                    
                    allEvents = allEvents.filter(e => String(e.id) !== String(id));
                    updateSidebarForCurrentMonth();
                } else {
                    showNotification(data.message || `Failed to delete ${type}`, 'danger');
                }
            })
            .catch(err => { 
                console.error(err); 
                showNotification(`Error deleting ${type}`, 'danger'); 
            });
        };
    });

    /**
     * DELETE EVENT FROM CALENDAR CLICK
     */
    function deleteEvent(event) {
        const type = event.extendedProps.type;
        const id = event.id;
        if (type === 'holiday') window.deleteHoliday(id);
        else if (type === 'test') window.deleteTest(id);
    }

    /**
     * MARK ALL SUNDAYS AS HOLIDAYS
     */
    function markAllSundays() {
        if (!calendar) return;

        const range = getCurrentMonthRange();
        if (!range) return;

        const { year, month, startDate, endDate } = range;

        showNotification('Marking all Sundays as holidays...', 'info');

        fetch('/calendar/mark-sundays', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ year, month: month + 1 })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                return showNotification(data.message || 'Failed to mark Sundays', 'danger');
            }

            showNotification('All Sundays marked as holidays', 'success');

            // FIXED: Add Sunday events with normalized dates
            const sundayEvents = [];
            const d = new Date(startDate);
            while (d <= endDate) {
                if (d.getDay() === 0) {
                    const dateStr = d.toISOString().split('T')[0];
                    const eventId = data.ids?.[dateStr] || `sunday-${d.getTime()}`;
                    
                    const sundayEvent = {
                        id: eventId,
                        title: 'Sunday Holiday',
                        start: normalizeDateString(dateStr),
                        allDay: true,
                        type: 'holiday',
                        backgroundColor: '#dc3545'
                    };
                    
                    calendar.addEvent(sundayEvent);
                    sundayEvents.push(sundayEvent);
                }
                d.setDate(d.getDate() + 1);
            }

            allEvents.push(...sundayEvents);
            updateSidebarForCurrentMonth();
        })
        .catch(err => { 
            console.error(err); 
            showNotification('Error marking Sundays', 'danger'); 
        });
    }

    /**
     * FORMAT DATE FOR DISPLAY
     */
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
    }

    /**
     * SHOW NOTIFICATION
     */
    function showNotification(message, type = 'info') {
        const container = document.querySelector('.flash-container');
        if (!container) return;

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        container.appendChild(alert);
        
        setTimeout(() => alert.remove(), 5000);
    }

    /**
     * AUTO-HIDE FLASH MESSAGES
     */
    function autoHideFlashMessages() {
        setTimeout(() => {
            document.querySelectorAll('.flash-container .alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    }

    /**
     * CAPITALIZE FIRST LETTER
     */
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    /**
     * WINDOW RESIZE HANDLER FOR CALENDAR
     */
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (calendar && typeof calendar.updateSize === 'function') {
                calendar.updateSize();
            }
        }, 250);
    });

})();