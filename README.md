# A Server-Side Calculated Field For Laravel Nova

This package contains two Nova fields required to do server-side calculations from the Nova client. 
The user can optionally override the calculated value on the form.

For a detailed write-up of the how-and-why of this field, please visit:

https://codebykyle.com/blog/laravel-nova-custom-calculated-field

## Installation

Install the package via composer:

`composer require codebykyle/calculated-field`


### Example
For example:
#### As a number
![Calculated Number Field](https://cbk-website.s3.amazonaws.com/calculated-field/number_calc_field.gif "Calculated Number Field")

#### As a string:
![Calculated String Field](https://cbk-website.s3.amazonaws.com/calculated-field/string_calc_field.gif "Calculated String Field")

##### Default
The Listener field will by default sum all numbers passed to it

### Usage
```php
<?php

use Codebykyle\CalculatedField\BroadcasterField;
use Codebykyle\CalculatedField\ListenerField;

class MyResource extends Resource
{
    public function fields(Request $request) {
        return [    
            BroadcasterField::make('Sub Total', 'sub_total'),
            BroadcasterField::make('Tax', 'tax'),
            ListenerField::make('Total Field', 'total_field')
        ];
    }
}
```

#### Overriding the Callback

```php

<?php
use Codebykyle\CalculatedField\BroadcasterField;
use Codebykyle\CalculatedField\ListenerField;

class MyResource extends Resource
{
    public function fields(Request $request) {
        return [    
            BroadcasterField::make('Sub Total', 'sub_total'),
            BroadcasterField::make('Tax', 'tax'),

            ListenerField::make('Total Field', 'total_field')
                ->calculateWith(function (Collection $values) {
                    $subtotal = $values->get('sub_total');
                    $tax = $values->get('tax');
                    return $subtotal + $tax;
                }),
        ];
    }
}
```


#### String Fields
```php

<?php
use Codebykyle\CalculatedField\BroadcasterField;
use Codebykyle\CalculatedField\ListenerField;

class MyResource extends Resource
{
    public function fields(Request $request) {
        return [    
            BroadcasterField::make('First Name', 'first_name')
                ->setType('string'),

            BroadcasterField::make('Last Name', 'last_name')
                ->setType('string'),

            ListenerField::make('Full Name', 'full_name')
                ->calculateWith(function (Collection $values) {
                    return $values->values()->join(' ');
                }),
        ];
    }
}
```


#### Multiple Calculated Fields

```php

<?php
use Codebykyle\CalculatedField\BroadcasterField;
use Codebykyle\CalculatedField\ListenerField;

class MyResource extends Resource
{
    public function fields(Request $request) {
        return [    
            BroadcasterField::make('Sub Total', 'sub_total')
                ->broadcastTo('total'),

            BroadcasterField::make('Tax', 'tax')
                ->broadcastTo('total'),

            ListenerField::make('Total Field', 'total_field')
                ->listensTo('total')
                ->calculateWith(function (Collection $values) {
                    $subtotal = $values->get('sub_total');
                    $tax = $values->get('tax');
                    return $subtotal + $tax;
                }),


            BroadcasterField::make('Senior Discount', 'senior_discount')
                ->broadcastTo('discount'),
    
            BroadcasterField::make('Coupon Discount', 'coupon_amount')
                ->broadcastTo('discount'),
    
            ListenerField::make('Total Discount', 'total_discount')
                ->listensTo('discount')
                ->calculateWith(function (Collection $values) {
                    $seniorDiscount = $values->get('senior_discount');
                    $couponAmount = $values->get('coupon_amount');
    
                    return $seniorDiscount + $couponAmount;
                })
        ];
    }
}
```
