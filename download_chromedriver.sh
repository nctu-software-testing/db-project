#!/bin/bash
URL_PREFIX="https://chromedriver.storage.googleapis.com"
CHROME_MAJOR_VERSION=$(google-chrome --product-version | cut -d. -f1)
CHROME_DRIVER_VERSION=$(wget -q -O- "$URL_PREFIX/LATEST_RELEASE_$CHROME_MAJOR_VERSION")
wget -q -O chromedriver_linux64.zip "$URL_PREFIX/$CHROME_DRIVER_VERSION/chromedriver_linux64.zip"
unzip -o chromedriver_linux64.zip
