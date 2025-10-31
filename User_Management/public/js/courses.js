// ============================================================================
// COURSES MANAGEMENT JAVASCRIPT - ORGANIZED VERSION
// ============================================================================

// ----------------------------------------------------------------------------
// SECTION 1: GLOBAL VARIABLES & UTILITY FUNCTIONS
// ----------------------------------------------------------------------------

let globalSubjects = []; // Track subjects in Create modal
let validSubjectsList = []; // Cache of valid subjects from backend

// Debounce function to limit API calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ----------------------------------------------------------------------------
// SECTION 2: SUBJECT VALIDATION (STRICT MODE)
// ----------------------------------------------------------------------------

// Load valid subjects list from backend
async function loadValidSubjects() {
    try {
        const response = await fetch('/courses/valid-subjects');
        validSubjectsList = await response.json();
        console.log('Loaded valid subjects:', validSubjectsList.length);
    } catch (error) {
        console.error('Error loading valid subjects:', error);
    }
}

// Check if subject is valid (exact match, case-insensitive)
function isValidSubject(subject) {
    if (!subject || subject.trim().length < 2) {
        return false;
    }
    
    const subjectLower = subject.toLowerCase().trim();
    return validSubjectsList.some(validSubject => 
        validSubject.toLowerCase() === subjectLower
    );
}

// Get exact subject match from valid list (preserves correct capitalization)
function getExactSubjectMatch(subject) {
    const subjectLower = subject.toLowerCase().trim();
    return validSubjectsList.find(validSubject => 
        validSubject.toLowerCase() === subjectLower
    ) || null;
}

// Fetch subject suggestions from backend
async function fetchSubjectSuggestions(query, suggestionsId) {
    if (query.length < 1 || !query.trim()) {
        const container = document.getElementById(suggestionsId);
        if (container) container.style.display = 'none';
        return;
    }

    try {
        const response = await fetch(`/courses/subject-suggestions?query=${encodeURIComponent(query)}`);
        const suggestions = await response.json();
        
        const suggestionsContainer = document.getElementById(suggestionsId);
        if (!suggestionsContainer) return;
        
        suggestionsContainer.innerHTML = '';
        
        if (suggestions.length > 0) {
            suggestions.forEach(subject => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = subject;
                item.onclick = function() {
                    const inputId = suggestionsId.replace('Suggestions', 'Input');
                    const inputField = document.getElementById(inputId);
                    if (inputField) {
                        inputField.value = subject;
                        suggestionsContainer.style.display = 'none';
                        
                        // Simulate Enter key press
                        const event = new KeyboardEvent('keypress', { key: 'Enter', bubbles: true });
                        inputField.dispatchEvent(event);
                    }
                };
                suggestionsContainer.appendChild(item);
            });
            suggestionsContainer.style.display = 'block';
        } else {
            const noMatch = document.createElement('div');
            noMatch.className = 'list-group-item text-muted';
            noMatch.textContent = '❌ No matching subjects found';
            suggestionsContainer.appendChild(noMatch);
            suggestionsContainer.style.display = 'block';
        }
    } catch (error) {
        console.error('Error fetching suggestions:', error);
    }
}

const debouncedFetchSuggestions = debounce(fetchSubjectSuggestions, 200);

// Show validation error
function showValidationError(message, inputField) {
    const existingError = inputField.parentElement.querySelector('.subject-error');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'subject-error text-danger small mt-1';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    inputField.parentElement.appendChild(errorDiv);
    
    // Shake animation
    inputField.classList.add('shake-input');
    setTimeout(() => inputField.classList.remove('shake-input'), 500);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 4000);
}

// Add subject tag with hidden input
function addSubjectTag(subject, container, isEditMode, courseId = null) {
    if (!container) return;
    
    const tag = document.createElement('span');
    tag.className = 'subject-tag';
    tag.innerHTML = `
        ${subject}
        <button type="button" class="remove-subject" onclick="removeSubjectTag(this, ${isEditMode})">×</button>
        <input type="hidden" name="subjects[]" value="${subject}">
    `;
    container.appendChild(tag);
}

// Remove subject tag
function removeSubjectTag(button, isEditMode) {
    const tag = button.parentElement;
    const subject = tag.querySelector('input').value;
    
    if (!isEditMode) {
        globalSubjects = globalSubjects.filter(s => s !== subject);
    }
    
    tag.remove();
}

// ----------------------------------------------------------------------------
// SECTION 3: CREATE MODAL SUBJECT HANDLING (STRICT VALIDATION)
// ----------------------------------------------------------------------------

function initializeCreateModalSubjects() {
    const subjectInput = document.getElementById('subjectInput');
    const subjectTags = document.getElementById('subjectTags');
    const suggestionsContainer = document.getElementById('subjectSuggestions');
    
    if (!subjectInput || !subjectTags) return;

    // Show suggestions as user types
    subjectInput.addEventListener('input', function() {
        debouncedFetchSuggestions(this.value, 'subjectSuggestions');
    });

    // STRICT: Only add if subject exists in valid list
    subjectInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const userInput = this.value.trim();
            
            if (!userInput) {
                return;
            }
            
            // Check if subject is valid (case-insensitive exact match)
            if (!isValidSubject(userInput)) {
                showValidationError(
                    `"${userInput}" is not a valid subject. Please select from suggestions or type the exact subject name.`,
                    this
                );
                return;
            }
            
            // Get exact match with correct capitalization
            const exactMatch = getExactSubjectMatch(userInput);
            
            // Check for duplicates
            const isDuplicate = globalSubjects.some(s => s.toLowerCase() === exactMatch.toLowerCase());
            
            if (isDuplicate) {
                showValidationError(`"${exactMatch}" is already added.`, this);
                return;
            }
            
            // Add the subject with correct capitalization
            globalSubjects.push(exactMatch);
            addSubjectTag(exactMatch, subjectTags, false);
            this.value = '';
            if (suggestionsContainer) suggestionsContainer.style.display = 'none';
            
            // Remove error if any
            const errorMsg = this.parentElement.querySelector('.subject-error');
            if (errorMsg) errorMsg.remove();
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (suggestionsContainer && !subjectInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.style.display = 'none';
        }
    });
}

// ----------------------------------------------------------------------------
// SECTION 4: EDIT MODAL SUBJECT HANDLING (STRICT VALIDATION)
// ----------------------------------------------------------------------------

function initializeEditModalSubjects() {
    document.querySelectorAll('[id^="editSubjectInput"]').forEach(input => {
        const courseId = input.id.replace('editSubjectInput', '');
        const tagsContainer = document.getElementById(`editSubjectTags${courseId}`);
        const suggestionsContainer = document.getElementById(`editSubjectSuggestions${courseId}`);
        
        if (!tagsContainer) return;

        // Load existing subjects
        const existingSubjects = JSON.parse(tagsContainer.dataset.subjects || '[]');
        existingSubjects.forEach(subject => addSubjectTag(subject, tagsContainer, true, courseId));

        // Show suggestions as user types
        input.addEventListener('input', function() {
            debouncedFetchSuggestions(this.value, `editSubjectSuggestions${courseId}`);
        });

        // STRICT: Only add if subject exists in valid list
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const userInput = this.value.trim();
                
                if (!userInput) {
                    return;
                }
                
                // Check if subject is valid
                if (!isValidSubject(userInput)) {
                    showValidationError(
                        `"${userInput}" is not a valid subject. Please select from suggestions or type the exact subject name.`,
                        this
                    );
                    return;
                }
                
                // Get exact match with correct capitalization
                const exactMatch = getExactSubjectMatch(userInput);
                
                // Check for duplicates
                const currentSubjects = Array.from(tagsContainer.querySelectorAll('input[name="subjects[]"]'))
                    .map(inp => inp.value.toLowerCase());
                
                if (currentSubjects.includes(exactMatch.toLowerCase())) {
                    showValidationError(`"${exactMatch}" is already added.`, this);
                    return;
                }
                
                addSubjectTag(exactMatch, tagsContainer, true, courseId);
                this.value = '';
                if (suggestionsContainer) suggestionsContainer.style.display = 'none';
                
                // Remove error if any
                const errorMsg = this.parentElement.querySelector('.subject-error');
                if (errorMsg) errorMsg.remove();
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (suggestionsContainer && !input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                suggestionsContainer.style.display = 'none';
            }
        });
    });
}

// Reset Create modal when closed
function setupCreateModalReset() {
    const createModal = document.getElementById('createCourseModal');
    if (createModal) {
        createModal.addEventListener('hidden.bs.modal', function() {
            globalSubjects = [];
            const subjectTags = document.getElementById('subjectTags');
            const subjectInput = document.getElementById('subjectInput');
            if (subjectTags) subjectTags.innerHTML = '';
            if (subjectInput) subjectInput.value = '';
            
            const form = this.querySelector('form');
            if (form) form.reset();
            
            // Remove any error messages
            const errors = this.querySelectorAll('.subject-error');
            errors.forEach(err => err.remove());
        });
    }
}

// Make functions globally accessible
window.removeSubjectTag = removeSubjectTag;

// ----------------------------------------------------------------------------
// SECTION 5: FILE UPLOAD PREVIEW
// ----------------------------------------------------------------------------

function initializeFileUploadPreview() {
    const importFile = document.getElementById('importFile');
    if (importFile) {
        importFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const preview = document.getElementById('filePreview');
                const previewText = document.getElementById('previewText');
                
                if (preview && previewText) {
                    previewText.innerHTML = `
                        <div class="mt-2">
                            📄 <strong>${file.name}</strong><br>
                            <small>Size: ${(file.size / 1024).toFixed(2)} KB</small>
                        </div>
                    `;
                    preview.classList.remove('d-none');
                }
            }
        });
    }
}

// ----------------------------------------------------------------------------
// SECTION 6: UI ENHANCEMENTS
// ----------------------------------------------------------------------------

// Auto-hide flash messages after 3 seconds
function initializeFlashMessages() {
    setTimeout(() => {
        document.querySelectorAll('.flash-container .alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);
}

// Sidebar toggle functionality
function initializeSidebarToggle() {
    const sidebar = document.querySelector('#sidebar');
    const toggleBtn = document.querySelector('#toggleBtn');
    const text = document.querySelector('#text');
    const right = document.querySelector('#right');

    if (!sidebar || !toggleBtn || !text || !right) return;

    let isCollapsed = false;

    // Initialize styling
    sidebar.style.transition = 'width 0.3s ease-in-out';
    sidebar.style.overflow = 'hidden';
    sidebar.style.width = '300px';

    const menuItems = sidebar.querySelectorAll('li, a, .nav-item');
    menuItems.forEach(item => {
        item.style.whiteSpace = 'nowrap';
    });

    toggleBtn.addEventListener('click', function() {
        if (isCollapsed) {
            sidebar.style.width = '26%';
            right.style.width = '100%';
            text.style.visibility = 'visible';
        } else {
            sidebar.style.width = '41px';
            right.style.width = '100%';
            text.style.visibility = 'hidden';
        }
        isCollapsed = !isCollapsed;
    });
}

// Search functionality
function initializeSearch() {
    const searchInput = document.querySelector('#searchInput');
    const tableBody = document.querySelector('#coursesTable');
    
    if (!searchInput || !tableBody) return;

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');
        
        let visibleCount = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update entry count
        const showText = document.querySelector('.show');
        if (showText && visibleCount > 0) {
            showText.textContent = `Showing ${visibleCount} entries`;
        }
    });
}

// ✅ NEW: Per-page dropdown handler (Fix 3)
function initializePerPageDropdown() {
    document.querySelectorAll('.dd .dropdown-menu .dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const perPage = e.currentTarget.textContent.trim();
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    });
}

// Entries per page dropdown (OLD - keeping for display update)
function initializeEntriesDropdown() {
    const entriesDropdown = document.querySelector('#number');
    if (!entriesDropdown) return;

    const dropdownItems = entriesDropdown.parentElement.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedValue = this.textContent.trim();
            entriesDropdown.textContent = selectedValue;
            console.log('Selected entries per page:', selectedValue);
        });
    });
}

// Table row hover effect
function initializeTableHover() {
    const tableRows = document.querySelectorAll('#coursesTable tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

// Delete confirmation
function initializeDeleteConfirmation() {
    const deleteForms = document.querySelectorAll('form[action*="courses"]');
    deleteForms.forEach(form => {
        if (form.querySelector('input[name="_method"][value="DELETE"]')) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        }
    });
}

// Form validation
function initializeFormValidation() {
    const createForm = document.querySelector('#createCourseModal form');
    if (!createForm) return;

    createForm.addEventListener('submit', function(e) {
        const courseName = this.querySelector('input[name="course_name"]')?.value.trim();
        const courseType = this.querySelector('select[name="course_type"]')?.value.trim();
        const className = this.querySelector('input[name="class_name"]')?.value.trim();
        const courseCode = this.querySelector('input[name="course_code"]')?.value.trim();

        if (!courseName || !courseType || !className || !courseCode) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }

        // Check if at least one subject is added
        if (globalSubjects.length === 0) {
            e.preventDefault();
            alert('Please add at least one subject');
            return false;
        }
    });
}

// ----------------------------------------------------------------------------
// SECTION 7: MAIN INITIALIZATION
// ----------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', async function() {
    console.log("Courses.js loaded successfully");

    // CRITICAL: Load valid subjects first
    await loadValidSubjects();

    // Initialize all features
    initializeCreateModalSubjects();
    initializeEditModalSubjects();
    setupCreateModalReset();
    initializeFileUploadPreview();
    initializeFlashMessages();
    initializeSidebarToggle();
    initializeSearch();
    initializePerPageDropdown(); // ✅ NEW: Added per-page dropdown handler
    initializeEntriesDropdown();
    initializeTableHover();
    initializeDeleteConfirmation();
    initializeFormValidation();

    console.log('All features initialized successfully');
});


// ============================================================================
// ADDITIONAL UI ENHANCEMENTS (from emp.js)
// ============================================================================

// ----------------------------------------------------------------------------
// Bootstrap popovers initialization
// ----------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap popovers (if any elements use them)
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].forEach(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));

    // Ensure Bootstrap modal focus behavior (optional, for accessibility)
    const myModal = document.getElementById('myModal');
    const myInput = document.getElementById('myInput');
    if (myModal && myInput) {
        myModal.addEventListener('shown.bs.modal', () => {
            myInput.focus();
        });
    }
});

// ----------------------------------------------------------------------------
// jQuery dropdown text update (for Bootstrap dropdowns)
// ----------------------------------------------------------------------------
$(document).on('click', '.dropdown-item', function() {
    const selectedText = $(this).text().trim();
    $(this).closest('.dropdown').find('.dropdown-toggle').text(selectedText);
});