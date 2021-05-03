# NWSLTR
This project contains a ready-made self-hosted mailing system written in PHP8, Nette Framework, Doctrine 2 and Vue.js

## System Requirements
- \>= PHP 8.0
- \>= MySQL 8
- Redis Server
- Composer
- Yarn + Webpack

## Installation
The ideal way of installing this project is via Composer:

```bash
$ composer create-project jvitasek/nwsltr
```

Or directly clone this repository:

```bash
$ git clone https://github.com/jvitasek/nwsltr.git
```

Once you have the project files, do the following:
1. Create an empty MySQL database
2. Create a new file config/test.neon and copy the contents of config/test.sample.neon in there
   
Now you have two options:

### Automatic installation (recommended)
1. (Optional, only if on UNIX) Set the right permissions to the init script: `chmod +x bin/init`
2. Run `bin init` which takes care of all the necessary configuration

### Manual Installation
1. Create directories log and temp in the root folder (`$ mkdir log temp && chmod +x log temp`)
2. `$ composer i`
3. `$ php bin/console orm:clear-cache:metadata`
4. `$ php bin/console o:s:u -f`
5. (Optional, only if on UNIX) `$ chmod +x bin/*`
6. `$ php bin/console doctrine:fixtures:load -n`
7. `$ bin/y`

## Usage

All use-cases require logging in first. Next sections will assume you are already logged in to the system.

### Creating recipient data:
It is important to first create some recipient data, since you cannot send a mailing without a recipient group with at least one subscribed recipient.

1. Access the Groups section in the left sidebar
2. Create the Group you want to send the mailing to (e.g. Golf Players)
3. Now you have 2 choices:
   1. Import recipients to the created group using an Excel file
    2. Add recipients manually in the Recipients section and indicate the created group in their Recipient Groups field

### Creating mailings:
Now that we have some recipient data in the system, we can go ahead and start building a new mailing campaign.

1. Go to the Mailings section in the left sidebar and click on the Create Mailing button in the top-right corner
2. In the editor, proceed as follows:
    1. Click on the pencil icon in the top-left corner of the screen and fill out the title, subject and the date of the mailing. Then indicate which Recipient Groups  should receive the mailing.
    2. Click on the plus icon in the top-left corner of the screen and use the components to build your mailing template.
3. (Optional) You can view the preview of your progress by clicking the Preview button in the top-right corner of the screen.
4. Once you are finished building the template, you can click the Back button and return to the Mailing datagrid.
5. Now you are ready to set the status of your newly created mailing to Ready. Based on the date that was set in the mailing editor, the system will automatically send the mailing once the date is reached (the crontab should ideally run every minute)