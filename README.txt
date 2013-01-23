==============
:::::NOTE:::::
==============
Please note that this is only a fork and not the official module which is
developed by PukunuiAustralia.

This fork extends the Certificate module by adding the course specific
borders, seals, signatures and watermarks. 

Borders, seals, signatures and watermarks are the images used during the
creation of the Certificate. In the original project was not possible
to confine specific borders to specific courses. What happens is that
all courses can see all borders, seals, signatures and watermarks.

This fork implements a filter that makes borders, seals, signatures and
watermarks visibile only for specific courses.

You will notice that every sub folder of the pix folder has now a new 
folder named "c". Inside this folder you should store 
the images that are going to be visible only by specific courses.

The visibility is enabled by the naming convention: COURSE_SHORTNAME_description.{jpg,png}

Example: pix/borders/c/course_007_purple.jpg

The border "course_007_purple" is going to be visible only inside
the certificates of the course that has course_007 as shortname.
For every other course this border is going to be invisible.

This applies also to seals, signatures and watermarks.

Please note that every image inside "c" folder
that doesn't follow the naming convention is going to be ignored.

Also note that, generally, the filename length shouldn't be longer 
than 30 chars while in the "c" folder the length is limited to 28 chars.
This is a limitation related to the tables of this module.
If you modify the lenght of the tables please modify also
the FILE_NAME_LENGTH constant in the lib.php file.

To install clone the repository:

git clone git://github.com/arael/moodle-mod_certificate.git

or download the compressed archive.


==============================================================================
FOLLOWS THE ORIGINALE README.txt file 
==============================================================================

QUICK INSTALL
=============

There are two installation methods that are available. Follow one of these, then
log into your Moodle site as an administrator and visit the notifications page
to complete the install.

==================== MOST RECOMMENDED METHOD - Git ====================

If you do not have git installed, please see the below link. Please note, it is 
not necessary to set up the SSH Keys. This is only needed if you are going to 
create a repository of your own on github.com.

Information on installing git - http://help.github.com/set-up-git-redirect/

Once you have git installed, simply visit the Moodle mod directory and clone 
git://github.com/PukunuiAustralia/moodle-mod_certificate.git, remember to 
rename the folder to certificate if you do not specify this in the clone command

Eg. Linux command line would be as follow -

git clone git://github.com/PukunuiAustralia/moodle-mod_certificate.git certificate

Once cloned, checkout the branch that is specific to your Moodle version.
eg, MOODLE_20_STABLE is for Moodle 2.0, MOODLE_21_STABLE is for 2.1

Use git pull to update this branch periodically to ensure you have the latest version.

==================== Download the certificate module. ====================

Visit https://github.com/PukunuiAustralia/moodle-mod_certificate, choose the branch 
that matches your Moodle version (eg. MOODLE_20_STABLE is for Moodle 2.0, MOODLE_21_STABLE 
is for 2.1) and download the zip, uncompress this zip and extract the folder. The 
folder will have a name similar to PukunuiAustralia-moodle-mod_certificate-c9fbadb, you 
MUST rename this to certificate. Place this folder in your mod folder in your Moodle 
directory. 

nb. The reason this is not the recommended method is due to the fact you have 
to over-write the contents of this folder to apply any future updates to the certificate 
module. In the above method there is a simple command to update the files.
