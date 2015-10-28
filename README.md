#Custom User Title

© 2015 - David King ([imkingdavid](http://www.thedavidking.com))

This is an extension for phpBB 3.1 that will let your users set their own custom user title.

The user title can be show above, below, or instead of the user's rank. There is a permission that is added to control whether or not users can set their own title.

##Requirements
- >= phpBB 3.1.6
- >= PHP 5.4

##Installation
You can install this on the latest copy of the develop branch (phpBB 3.1-dev) by following the steps below.

**Manual:**

1. If there is not yet an `./ext/imkingdavid/customusertitle/` folder tree starting from your board root, create one.
2. Copy the entire contents of this repo into that folder you just created (You can leave out the *.md files, .gitignore, and the .git folder).
3. Navigate in the ACP to `Customise -> Manage extensions -> Extensions`.
4. Click Enable.

**Git:**

1. From the board root run the following git command:
`git clone https://github.com/imkingdavid/customusertitle.git ext/imkingdavid/customusertitle`
2. Go to `ACP -> Customise -> Manage extensions -> Extensions`
3. Click Enable next to the [pre]fixed extension.

##Uninstallation
In the ACP -> Customise -> Manage Extensions -> Extensions module, you can click one of the following:
- **Disable:** This keeps the Extension data and schema intact but prevents it from running. Prefixes will remain in the database but will not appear with topic titles, and the administration area will be unavailable. When you re-enable the extension, all functionality will become active again.
- **Delete data:** This destroys any data added by the extension, and reverts any schema changes it made. You can re-enable the extension, but all prefixes will be gone.
