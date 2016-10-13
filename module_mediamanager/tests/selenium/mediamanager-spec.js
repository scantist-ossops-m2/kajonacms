"use strict";

var SeleniumUtil = requireHelper('/util/SeleniumUtil.js');

describe('module_mediamanager', function() {

    beforeEach(function() {
        browser.ignoreSynchronization = true;
    });

    it('test list', function() {
        SeleniumUtil.gotToUrl('index.php?admin=1&module=mediamanager&action=list').then(function() {
            expect(browser.driver.findElement(by.id('moduleTitle')).getText()).toEqual('Medien-Manager');
        });
    });

});
