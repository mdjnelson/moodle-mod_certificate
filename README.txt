Certificate 3.0 for Moodle



Version 3.2 20061012
This version has been updated to work in Moodle 1.7. 

Version 3.1 2006081700
Increased length of course name record
General debugging and code fixes
Fixed symbol printing in course name, mod name and student name

Version 3.1 2006080100
The certificate module has been re-written to better comply with standard Moodle coding and to add
new features:

Added backup/restore.
Added teacher reporting.
Added student certificate review.
Added email teachers.
Added print functions for letter size paper for border and watermark images.  Fixed (hopefully) all certificate alignment.
Reformatted border images to jpeg to make them smaller.
Created the type folder.  New types can be added without having to change the core code.
New/changed certificate options:
Save: Can choose to save students' certificates in moddata.
Delivery:  Open in browser, Force download, email (Thanks Marion DeGroot).
Print Date: Added course end date, more can be added.
Date Format:  Updated for unicode.
Print Code: Updated.
Grade Format: Added three formats


To do:

Needs a cron function/updated email features.
Groups not working in report???
Add more date options?
Add download report to Excel

************************************
Version 3.0 updated for 1.6 by Chardelle Busch.  
Added new fpdf security feature so that downloaded certificates can 
only be printed, not edited.
Added mod grade name (Thanks Mike Churchward)

************************************
Prior Versions:

Updated by David T. Cannon, July, 2005
Updated to fpdf class
Added mod grades.
Course grade added by Patrick Jeitler

************************************
Created by Hugo Salgado, July, 2004