"use strict";

var SeleniumUtil = require('../../../../core/_buildfiles/jstests/selenium/util/SeleniumUtil.js');

describe('module_navigation', function() {

    beforeEach(function() {
        browser.ignoreSynchronization = true;
    });

    it('test list', function() {
        SeleniumUtil.gotToUrl('index.php?admin=1&module=navigation&action=list');

        expect(browser.driver.findElement(by.id('moduleTitle')).getText()).toEqual('Navigationen');
    });

});
