<?php

namespace App\Helpers;

class IndiaData
{
    public static function getStates()
    {
        return [
            'Andhra Pradesh',
            'Arunachal Pradesh',
            'Assam',
            'Bihar',
            'Chhattisgarh',
            'Goa',
            'Gujarat',
            'Haryana',
            'Himachal Pradesh',
            'Jharkhand',
            'Karnataka',
            'Kerala',
            'Madhya Pradesh',
            'Maharashtra',
            'Manipur',
            'Meghalaya',
            'Mizoram',
            'Nagaland',
            'Odisha',
            'Punjab',
            'Rajasthan',
            'Sikkim',
            'Tamil Nadu',
            'Telangana',
            'Tripura',
            'Uttar Pradesh',
            'Uttarakhand',
            'West Bengal',
            'Delhi',
            'Chandigarh',
            'Puducherry'
        ];
    }

  public static function getCities($state = null)
{
    $citiesData = [
        'Andhra Pradesh' => ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool', 'Rajahmundry', 'Kakinada', 'Tirupati', 'Anantapur', 'Kadapa', 'Eluru', 'Ongole', 'Nandyal', 'Machilipatnam', 'Adoni'],
        
        'Arunachal Pradesh' => ['Itanagar', 'Naharlagun', 'Pasighat', 'Namsai', 'Tawang', 'Ziro', 'Bomdila', 'Tezu', 'Seppa', 'Aalo'],
        
        'Assam' => ['Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon', 'Tinsukia', 'Tezpur', 'Bongaigaon', 'Diphu', 'Dhubri', 'North Lakhimpur', 'Karimganj', 'Sivasagar', 'Goalpara', 'Barpeta'],
        
        'Bihar' => ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Darbhanga', 'Purnia', 'Munger', 'Chapra', 'Begusarai', 'Katihar', 'Saharsa', 'Hajipur', 'Sasaram', 'Dehri', 'Siwan', 'Motihari', 'Nawada', 'Bagaha', 'Buxar', 'Kishanganj'],
        
        'Chhattisgarh' => ['Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg', 'Rajnandgaon', 'Jagdalpur', 'Raigarh', 'Ambikapur', 'Mahasamund', 'Dhamtari', 'Chirmiri', 'Bhatapara', 'Dalli-Rajhara', 'Naila Janjgir'],
        
        'Goa' => ['Panaji', 'Vasco da Gama', 'Margao', 'Mapusa', 'Ponda', 'Bicholim', 'Curchorem', 'Sanquelim', 'Cuncolim', 'Quepem'],
        
        'Gujarat' => ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar', 'Jamnagar', 'Gandhinagar', 'Junagadh', 'Gandhidham', 'Anand', 'Navsari', 'Morbi', 'Mehsana', 'Surendranagar', 'Bharuch', 'Vapi', 'Nadiad', 'Porbandar', 'Ankleshwar', 'Godhra'],
        
        'Haryana' => ['Faridabad', 'Gurugram', 'Panipat', 'Ambala', 'Yamunanagar', 'Rohtak', 'Hisar', 'Karnal', 'Sonipat', 'Panchkula', 'Bhiwani', 'Sirsa', 'Bahadurgarh', 'Jind', 'Thanesar', 'Kaithal', 'Rewari', 'Palwal', 'Hansi', 'Narnaul'],
        
        'Himachal Pradesh' => ['Shimla', 'Dharamshala', 'Solan', 'Mandi', 'Palampur', 'Baddi', 'Nahan', 'Sundernagar', 'Kullu', 'Chamba', 'Una', 'Hamirpur', 'Bilaspur', 'Kangra', 'Parwanoo'],
        
        'Jharkhand' => ['Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro', 'Deoghar', 'Phusro', 'Hazaribagh', 'Giridih', 'Ramgarh', 'Medininagar', 'Chirkunda', 'Dumka', 'Chaibasa', 'Gumla', 'Sahibganj'],
        
        'Karnataka' => ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum', 'Davanagere', 'Bellary', 'Bijapur', 'Shimoga', 'Tumkur', 'Raichur', 'Bidar', 'Hospet', 'Hassan', 'Gadag', 'Udupi', 'Robertsonpet', 'Bhadravati', 'Chitradurga', 'Kolar'],
        
        'Kerala' => ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam', 'Palakkad', 'Alappuzha', 'Malappuram', 'Kannur', 'Kottayam', 'Kasaragod', 'Pathanamthitta', 'Idukki', 'Wayanad', 'Ernakulam'],
        
        'Madhya Pradesh' => ['Indore', 'Bhopal', 'Jabalpur', 'Gwalior', 'Ujjain', 'Sagar', 'Dewas', 'Satna', 'Ratlam', 'Rewa', 'Murwara', 'Singrauli', 'Burhanpur', 'Khandwa', 'Bhind', 'Chhindwara', 'Guna', 'Shivpuri', 'Vidisha', 'Damoh'],
        
        'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik', 'Aurangabad', 'Solapur', 'Kolhapur', 'Amravati', 'Navi Mumbai', 'Sangli', 'Malegaon', 'Akola', 'Latur', 'Dhule', 'Ahmednagar', 'Chandrapur', 'Parbhani', 'Jalgaon', 'Bhiwandi', 'Nanded', 'Ichalkaranji', 'Jalna', 'Ambarnath', 'Ulhasnagar'],
        
        'Manipur' => ['Imphal', 'Thoubal', 'Bishnupur', 'Churachandpur', 'Kakching', 'Ukhrul', 'Senapati', 'Tamenglong', 'Jiribam', 'Moreh'],
        
        'Meghalaya' => ['Shillong', 'Tura', 'Nongstoin', 'Jowai', 'Baghmara', 'Williamnagar', 'Resubelpara', 'Nongpoh', 'Mairang', 'Mawkyrwat'],
        
        'Mizoram' => ['Aizawl', 'Lunglei', 'Champhai', 'Serchhip', 'Kolasib', 'Lawngtlai', 'Saiha', 'Mamit', 'Hnahthial', 'Khawzawl'],
        
        'Nagaland' => ['Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha', 'Zunheboto', 'Phek', 'Mon', 'Longleng', 'Kiphire', 'Peren'],
        
        'Odisha' => ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur', 'Sambalpur', 'Puri', 'Balasore', 'Bhadrak', 'Baripada', 'Jharsuguda', 'Jeypore', 'Bargarh', 'Balangir', 'Rayagada', 'Bhawanipatna', 'Dhenkanal', 'Barbil', 'Kendujhar', 'Sunabeda', 'Jatani'],
        
        'Punjab' => ['Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda', 'Mohali', 'Pathankot', 'Hoshiarpur', 'Moga', 'Batala', 'Malerkotla', 'Khanna', 'Phagwara', 'Muktsar', 'Barnala', 'Rajpura', 'Firozpur', 'Kapurthala', 'Faridkot', 'Sangrur'],
        
        'Rajasthan' => ['Jaipur', 'Jodhpur', 'Udaipur', 'Kota', 'Bikaner', 'Ajmer', 'Alwar', 'Bhilwara', 'Sikar', 'Pali', 'Sri Ganganagar', 'Bharatpur', 'Tonk', 'Kishangarh', 'Beawar', 'Hanumangarh', 'Dhaulpur', 'Gangapur City', 'Sawai Madhopur', 'Churu', 'Jhunjhunu', 'Barmer', 'Nagaur', 'Chittorgarh', 'Banswara'],
        
        'Sikkim' => ['Gangtok', 'Namchi', 'Gyalshing', 'Mangan', 'Rangpo', 'Jorethang', 'Naya Bazar', 'Singtam', 'Ravangla', 'Pelling'],
        
        'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli', 'Tiruppur', 'Erode', 'Vellore', 'Thoothukudi', 'Thanjavur', 'Dindigul', 'Ranipet', 'Sivakasi', 'Karur', 'Kanchipuram', 'Kumbakonam', 'Nagercoil', 'Cuddalore', 'Hosur', 'Tiruvannamalai', 'Ambur', 'Pollachi', 'Rajapalayam', 'Pudukkottai'],
        
        'Telangana' => ['Hyderabad', 'Warangal', 'Nizamabad', 'Khammam', 'Karimnagar', 'Ramagundam', 'Mahbubnagar', 'Nalgonda', 'Adilabad', 'Suryapet', 'Siddipet', 'Miryalaguda', 'Jagtial', 'Mancherial', 'Nirmal', 'Kothagudem', 'Bodhan', 'Sangareddy', 'Metpally', 'Zahirabad'],
        
        'Tripura' => ['Agartala', 'Dharmanagar', 'Udaipur', 'Kailashahar', 'Bishalgarh', 'Teliamura', 'Khowai', 'Belonia', 'Melaghar', 'Mohanpur'],
        
        'Uttar Pradesh' => ['Lucknow', 'Kanpur', 'Ghaziabad', 'Agra', 'Varanasi', 'Meerut', 'Allahabad', 'Bareilly', 'Aligarh', 'Moradabad', 'Saharanpur', 'Gorakhpur', 'Noida', 'Firozabad', 'Jhansi', 'Muzaffarnagar', 'Mathura', 'Rampur', 'Shahjahanpur', 'Farrukhabad', 'Maunath Bhanjan', 'Hapur', 'Etawah', 'Mirzapur', 'Bulandshahr', 'Sambhal', 'Amroha', 'Hardoi', 'Fatehpur', 'Raebareli'],
        
        'Uttarakhand' => ['Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur', 'Kashipur', 'Rishikesh', 'Pithoragarh', 'Ramnagar', 'Nainital', 'Tehri', 'Pauri', 'Almora', 'Bageshwar', 'Champawat'],
        
        'West Bengal' => ['Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri', 'Bardhaman', 'Malda', 'Baharampur', 'Habra', 'Kharagpur', 'Shantipur', 'Dankuni', 'Dhulian', 'Ranaghat', 'Haldia', 'Raiganj', 'Krishnanagar', 'Nabadwip', 'Medinipur', 'Jalpaiguri', 'Balurghat', 'Basirhat', 'Bankura', 'Chakdaha', 'Darjeeling'],
        
        'Andaman and Nicobar Islands' => ['Port Blair', 'Diglipur', 'Car Nicobar', 'Bamboo Flat', 'Rangat', 'Campbell Bay', 'Nancowry', 'Mayabunder', 'Little Andaman', 'Hut Bay'],
        
        'Chandigarh' => ['Chandigarh', 'Mani Majra', 'Panchkula (nearby)'],
        
        'Dadra and Nagar Haveli and Daman and Diu' => ['Daman', 'Diu', 'Silvassa', 'Amli', 'Naroli', 'Rakholi', 'Samarvarni', 'Khanvel', 'Dahanu Road (nearby)'],
        
        'Delhi' => ['New Delhi', 'North Delhi', 'South Delhi', 'East Delhi', 'West Delhi', 'Central Delhi', 'North East Delhi', 'North West Delhi', 'South East Delhi', 'South West Delhi', 'Shahdara', 'Dwarka', 'Rohini', 'Najafgarh', 'Narela'],
        
        'Jammu and Kashmir' => ['Srinagar', 'Jammu', 'Anantnag', 'Baramulla', 'Sopore', 'Kathua', 'Udhampur', 'Punch', 'Rajauri', 'Kupwara', 'Pulwama', 'Doda', 'Shopian', 'Ganderbal', 'Budgam', 'Bandipora', 'Kulgam', 'Kishtwar', 'Ramban', 'Reasi'],
        
        'Ladakh' => ['Leh', 'Kargil', 'Nubra', 'Zanskar', 'Drass', 'Neyoma', 'Panamik', 'Turtuk', 'Sankoo', 'Diskit'],
        
        'Lakshadweep' => ['Kavaratti', 'Agatti', 'Amini', 'Andrott', 'Kadmat', 'Kalpeni', 'Minicoy', 'Kiltan', 'Chetlat', 'Bitra'],
        
        'Puducherry' => ['Puducherry', 'Karaikal', 'Mahe', 'Yanam', 'Ozhukarai', 'Villianur', 'Ariyanpet', 'Nettapakkam', 'Bahour', 'Kirumampakkam']
    ];

    if ($state && isset($citiesData[$state])) {
        return $citiesData[$state];
    }

    return $citiesData;
}
}