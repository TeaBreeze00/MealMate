# MealMate
MealMate is a full-stack web application designed to connect clients with local vendors and delivery personnel to provide a seamless tiffin (meal) delivery service. The application ensures that clients can easily place orders, vendors can manage their offerings, and delivery personnel can efficiently fulfill orders.

### Feature List

- **Secure Account Registration and Authentication:**
  - Users can register and create accounts securely.
  - Implements authentication mechanisms to ensure secure access to user accounts.

- **Restaurant Browsing and Ordering:**
  - Enables users to browse a variety of restaurants offering food for delivery.
  - Provides a user-friendly interface for selecting menu items and placing orders.

- **Real-Time Menu Updates:**
  - Displays real-time updates of menu items, including availability and pricing.
  - Ensures customers have access to the latest offerings from restaurants.

- **Customized Ordering Experience:**
  - Allows users to customize their orders based on preferences and dietary requirements.
  - Supports special instructions or requests for individual menu items.

- **Order Tracking and Status Updates:**
  - Provides real-time tracking of orders from placement to delivery.
  - Sends notifications and updates regarding order status changes, ensuring transparency and keeping customers informed.

- **Secure Payment Processing:**
  - Integrates secure payment gateways to facilitate safe and hassle-free transactions.
  - Supports multiple payment methods, including credit/debit cards, digital wallets, and cash on delivery.

  ### DEVELOPMENT REFERENCE 💻
- Create a config.php file in project directory and include the following code inside the file with appropriate changes to include your credentials.
    ```PHP
    <?php

    $DB_USER = "ora_CWL";			// change "cwl" to your own CWL
    $DB_PASS = "a99999999";	        // change to 'a' + your student number
    $DB_HOST = "dbhost.students.cs.ubc.ca:1522/stu";

    ?>
    ```

- Command for setting permissions of public_html
    - General
        ```BASH
        chmod 711 ~/public_html; chmod 711 ~
        ``` 
    - Specific `file.php`
        ```BASH
        chmod 711 ~/public_html/base.php
        ```
    - Command for permissions
        ```BASH
        ls -la ~/public_html
        ```
- Command for transfering files to cs servers:
    - Replace $CWL with CWL and $file_ext appropriately, `Note:` Each group member may have to change command slightly according to how the directories are setup, this is a good starting point
    ```BASH
    scp ./project/$file_ext $CWL@remote.students.cs.ubc.ca:/home/a/$CWL/public_html
    ```
### Setting up the project (for group members) ✔
1) SSH into cs dep servers
1) Go to ~/public_html directory
    ```BASH
    cd ~/public_html
    ```
    `NOTE:` create the directory if it doesn't exist with `mkdir ~/public_html`
1) Clone the repo
    ```BASH
    git clone https://github.com/TeaBreeze00/MealMate.git
    ```
1) Set executable permissions for php files in pages folder of project
    ```BASH
    chmod -R 711  ~/fileLocation/MealMate
    ```
1) Create config.php file in project directory for database credentials
    ```BASH
   touch ~/fileLocation/MealMate/config.php
    ```
1) Modify the config.php file using nano
    ```BASH
    nano ~/public_html/MealMate/project/config.php
    ```
1) Copy paste the following code and change CWL, student number to appropriate values
    ```PHP
    <?php

        $DB_USER = "ora_CWL";			    // change "cwl" to your own CWL
        $DB_PASS = "a11223344";	            // change to 'a' + your student number
        $DB_HOST = "dbhost.students.cs.ubc.ca:1522/stu";

    ?>
    ```



1) Exit the editor by the following key presses:
    1) `Ctrl + s`
    1) `Ctrl + x`
1) The site should now be public at the link below with your CWL
    ```txt
    https://www.students.cs.ubc.ca/~CWL/MealMate/project/pages/login.php
    ```
1) For updating the files simply pull the latest files from github

---
## BUGS
1) Login page: passport number=1000000, email=john.doe@gmail.com , password=ilikefrogs
---
## LINKS 🚪
- [SQL Plus on dep servers](https://www.students.cs.ubc.ca/~cs-304/resources/sql-plus-resources/sql-plus-setup.html)
- [Getting started with PHP OCi (External)](https://reintech.io/blog/a-comprehensive-guide-to-php-oci8-library-for-oracle-database-access)
- [PHP OCI Documentation (External)](https://www.php.net/manual/en/book.oci8.php)
- [Project Overview (304)](https://www.students.cs.ubc.ca/~cs-304/resources/project-overview.html)
- [PHP Getting Started (304)](https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/php-setup.html)
- [PHP Wrapper file for error reporting (304)](https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/php-setup.html#debugging)