Certificate 1.0 for moodle

Dave T. Cannonn
2005-09-15


1. Certificate is meant to be used with activity locking, You may need to uncomment the code

// /// Activity Locking ///
..code..
/// End Activity Locking ///

in the view.php file. Since Activity Locking is still evolving, you should insert current locking code in here.


2. The date sometimes displays in 1970
There are two lines, by commenting them out and uncommenting the line next to it may work for different systems: 

mod/certificate/view.php line 88

block/certificate/index.php line 71

I understand this is messy hack, but I have not yet solved the problem for different systems and time.

Certificate Module

Release state: 0.7 Beta

This document will help you modify the certificate module to suite you own needs. Warning: modifiing too many of the files may make it difficult to upgrade later.
Certificate Types

The certificate types list is defined in the lang/<en>/certificate.php file. There is a string definitrion that will add the certificate type to the pulldown list:

    $string['type0'] = 'Completion (H)';

The '0'(zero) represents the position on the pulldown, zero being the first option. You can modify the value to any name you want for that certificate. You can add a new certificate type by creating a new string definition with the next iteration number '1'(one), ie:

    $string['type1'] = 'My new certificate (V)';

A (V) or (H) is placed in thew string to identify the page layout, horizontal or vertical.

After you create a new certificate type, you may want to add the text for the certificate next. These strings can be named anything you want, but should have the numeric value you set for the new certificate following the name. Our definition now looks like:

    $string['type1'] = 'My new certificate (V)';
    $string['title1'] = 'CONGRADULATION';
    $string['intro1'] = 'You passed my course';


Border Style

The border files are are in the pix/borders/ directory. Any files you place in the directory will display in the border style pulldown. The files names are as follows:

<bordername>-<color>.png.

    0 Black
    1 Brown
    2 Blue
    3 Green

A new border you place in this directory with 2 colors would lookliker:

    OrnateBorder-blue.png
    OrnateBorder-green.png

Border Color

This is a fixed list, but can be modified in mod.html, and updating the language file.
Print watermark

The watermark is a faded background image for the certificate. These images are in the pix/watermarks/ directory. It is up to you create the faded image and place it here.
Date Format

This is a fixed list that can be modified in the mod.html file.
Print Certificate Number

This is an internal algarythm to generate the certificate code.
Print Grade

This pulldown is generated from a call to a lib.php routine to find activities in the class that have a grade option.
Print Signature

The signature is a png image that can be placed on the certificate. These images are in the pix/signatures/ directory.
Print Teacher

This will print up to 3 teacher names from the current group/course teacher list. The code for this
Print Seal

The seal files are are in the pix/seals/ directory. Any files you place in the directory will display in the seal pulldown. There are sample images here. You may also want to place a logo.
 

Version 0.7 - David T. Cannon 
