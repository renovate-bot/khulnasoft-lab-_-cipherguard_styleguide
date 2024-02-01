# Developers FAQ
## Prerequisite
Make sure you have the developement dependencies install.
```
composer install --dev
```

## How do I run the unit tests
- Configure your test database in app.php datasources section.
- Run phpunit:
```
composer test
```

## How do I check the code standards
- To display the error and warning
```
composer cs-check
```
- To autofix what is fixable
```
composer cs-fix
```

## How to regenerate the fixtures
```
sudo su -s /bin/bash -c "./bin/cake CipherguardTestData.fixturize default" www-data
```

## How do I contribute to the js application

Clone the appjs repository in a separate folder
```
git clone https://github.com/khulnasoft/cipherguard-appjs.git
```

In your cipherguard_api folder install the javascript dependencies
```
npm install
```

Link the source of cipherguard-appjs project to your cipherguard_api project
```
cd node_modules
rm -fr cipherguard-appjs
npm link ../../cipherguard-appjs
cd ../
```

Listen to any change on the cipherguard-appjs product
```
grunt appjs-watch
```

If you want to save the browser refresh operation, and you are aware about the security implication, you can
install browser-sync
```
npm install grunt-browser-sync
```

Listen to the appjs change and refresh the browser
```
grunt appjs-watch-browser-sync
```

## How do I contribute to the translation

For contributing to the translations of this repository, you will need to create an account and propose changes at https://cipherguard.crowdin.com/.

