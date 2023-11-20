# Scale Documentation

## How the scale system works with our Web platform

The scale system (RS-232) interfaces using COM Ports in the computer system. For the accessibility of data passing through the scale system, we create a local HTTP interface using php source code (Xampp/Laragon with Apache/Nginx/MySQL Setup). The local HTTP interface also responsible for the decode mechanism of the scale data stream coming in a synchronous fashion.  


## Requirements

- Windows/Linux/Mac PC with PHP8.1 installation capabilities
- Web browser (Chrome/Edge/Firefox)
- Scale machine (hardware) connected and configured to the computer

## Installation

To install the scale software system in a computer, you need to be connected in a scale machine (hardware) in that computer system. To integrate the scale software system -  
- Install PHP8.1 with php `dio` extension for php8.1. and enable it in php.ini configuration file. 
- Install Apache2/Nginx web server and enable PHP8.1 configuration. Or, Install Xampp/Laragon (that packed php+apache both)
- Download scale project source files and move to the web-root. For xampp, its `C:\\xampp\\htdocs` and for Laragon its `C:\\laragon\\www`
- Put the project in a directory name `scale` and code as named `read-scale.php`. So, ultimate scale project is a one file live in `C:\\laragon\\www\scale\\read-scale.php`. The backend system configured for `http://scale.test/read-scale.php` file for reading data stream. Laragon will automatically make `scale.test` as a http project so do not do anything here if use Laragon. But for xampp user, scale.test need to be added into the hosts file of the system (Windows/Linux) and point to the appropriate directory (like `C:\\xampp\\htdocs\\scale`). 

- Go to browser and enable https vs http communication. For chrome, write `chrome://flags` in the url input box and hit enter key, this will bring menus to select from. Disable `Block insecure private network request`. To get this menu quickly, enter "private" in the search box. For edge write `edge://flags` in the url.

December 2022 Update:
Chrome team moved this setting under this flag. Go to this url and enable it. 
`chrome://flags/#allow-insecure-localhost`
`chrome://flags/#block-insecure-private-network-requests`


Other ways:

reg add HKEY_CURRENT_USER\SOFTWARE\Policies\Google\Chrome /t REG_DWORD /v InsecurePrivateNetworkRequestsAllowed /d 1 /f


## Usage 
- Go to the web platform http://dotbookerp.com
- Login to scale engineer dashboard
- Go scale related page and click on weight input and buttons

## FAQ
- Scale data not coming, what should I do? 
  > Scale data should come immediately after one click on button or input controls, if not then try clicking multiple time and keep patience. and ensure installation steps followed properly. Contact support team for getting direct help, if needed.

