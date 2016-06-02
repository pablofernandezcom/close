<?php
require_once 'library/Requests.php';

Requests::register_autoloader();

// name :: company name
 //    'company', # multiple contacts will be grouped if company names match
 //    'url',
 //    'status',
 //    'contact', # name of contact
 //    'title',
 //    'email',
 // 'phone', # recommended to start with "+" followed by country code (e.g., +1 650 555 1234)
 //    'mobile_phone',
 //    'fax',
 //    'address',
 //    'address_1', # if address is missing, address_1 and address_2 will be combined to create it.
 //    'address_2', # if address is missing, address_1 and address_2 will be combined to create it.
 //    'city',
 //    'state',
 //    'zip',
 //    'country',
$lead = array(

'name' => 'Test Name',
'status' => 'Web Signup',
'contacts' => [
                        [
                            'name' => 'Test Contact',
                            'title' => 'Test Title',
                            'date_updated' => '01-01-2015',
                            'created_by' => 'dasflkj32lkjs',
                            'organization_id' => 'sdalfkj2l2jk',
                            'phones' => [
                                [
                                    'phone' => '23211434332',
                                    'phone_formatted' => '+49 23 21 1434 332',
                                ]
                            ],
                            'emails' => [
                                [
                                    'email' => 'testemail@maihhhhhl.com',
                                ]
                            ],
                            'urls' => [
                                [
                                    'url' => 'www.test.com',
                                    'type' => 'office'
                                ]
                            ]
                        ]
                    ]
                    
                );


$headers = array('Content-Type' => 'application/json');
$options = array('auth' => array('XXX', ''));
$request = Requests::post('https://app.close.io/api/v1/lead/', $headers, json_encode($lead),  $options);

echo "SUCCESS";
?>
