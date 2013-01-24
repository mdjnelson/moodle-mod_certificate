NOTE: THIS IS A FORK. IT PROVIDES SOME ADDITIONAL FEATURES. PLEASE READE BELOW.
==============================================================================

==================== ADDITIONAL FEATURES ====================
This fork adds the Course protected images mechanism.

The Course protected images mechanism allows the user to upload images
that are visible only from the course specified during the upload.

The uploading form has been extended by adding two fields:
- Limit to one course (Checkbox)
  Selecting this checkbox is going to place the uploaded image inside
  the $CFG->data_root/mod/certificate/image_type/course/$COURSE->shortname
  folder. The images are then going to be visible only form the course
  with the same shortname.

- Course Shortname (Textfield)
  This field specifies the Course shortname to use during the upload.
  Thus the file is going to be placed in the directory specified by
  the input of this field. Before proceeding with the update the module
  validates the input by checking the existence of the course.

This is particularly useful for the Signature images because
it avoids the signatures to be visible in all other courses once they
are uploaded.

This fork also adds the Italian language support.



QUICK INSTALL
=============

There are two installation methods that are available. Follow one of these, then log into your Moodle site as an administrator and visit the notifications page to complete the install.

==================== MOST RECOMMENDED METHOD - Git ====================

If you do not have git installed, please see the below link. Please note, it is not necessary to set up the SSH Keys. This is only needed if you are going to create a repository of your own on github.com.

Information on installing git - http://help.github.com/set-up-git-redirect/

Once you have git installed, simply visit the Moodle mod directory and clone git://github.com/markn86/moodle-mod_certificate.git, remember to rename the folder to certificate if you do not specify this in the clone command

Eg. Linux command line would be as follow -

git clone git://github.com/markn86/moodle-mod_certificate.git certificate

Use git pull to update this repository periodically to ensure you have the latest version.

==================== Download the certificate module. ====================

Visit https://github.com/markn86/moodle-mod_certificate and download the zip, uncompress this zip and extract the folder. The folder will have a name similar to markn86-moodle-mod_certificate-c9fbadb, you MUST rename this to certificate. Place this folder in your mod folder in your Moodle directory.

nb. The reason this is not the recommended method is due to the fact you have to over-write the contents of this folder to apply any future updates to the certificate module. In the above method there is a simple command to update the files.
