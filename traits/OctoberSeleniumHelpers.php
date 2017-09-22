<?php

/**
 * Trait with Selenium 2 helper methods for October
 */

trait OctoberSeleniumHelpers
{
    protected function signInToBackend()
    {
        $this->visit(TEST_SELENIUM_BACKEND_URL)
             ->type(TEST_SELENIUM_USER, 'login')
             ->type(TEST_SELENIUM_PASS, 'password')
             ->findElement("Login button", "//button[@type='submit']")
             ->click();
        return $this;
    }

    /**
    * Usefull method for backend to interact
    */
    protected function waitForFlashMessage()
    {
        return $this->waitForElementsWithClass('flash-message');
    }

    /**
     * Check the checkbox of first row in backend list
     * @return $this
     */
    protected function checkFirstRowInBackend()
    {
        $this->findElement("First row", "//label[@for='Lists-checkbox-1']")
             ->click();
        $this->hold(3);

        return $this;
    }

    /**
     * Get record ID from backend list using search form
     * @param   $uniqueValue unique value for searching in searchform
     * @param   $pageUrl backend page URL where the list resides
     * @return  $id record ID
     */
    protected function getRecordID($uniqueValue, $pageUrl = '')
    {
        if (!empty($pageUrl)) {
            $this->visit($pageUrl);
        }
        $this->typeInBackendSearch($uniqueValue, true);
        $this->hold(2); //TODO wait for ajax to reload list
        $link = $this->findElement("Link for: ".$uniqueValue, '//*[@id="Lists"]/div/table/tbody/tr[1]/td[2]/a')
                     ->attribute('href');
        $linkParams = explode("/", $link);
        //TODO: try catch
        $id = end($linkParams);
        return $id?(int)$id:false;
    }

    /**
     * click row in backend list containig the $uniqueValue
     * @param  $uniqueValue what to search for in list
     * @return $this
     */
    protected function clickRowInBackendList($uniqueValue)
    {
        //TODO still not working
        $this->findElement($uniqueValue, "//td[contains(text(),{$uniqueValue})]")->click();
        dd("clicking row succeed");
        return $this;
    }

    /**
     * Check the checkbox of row with $id in backend list
     * @param $id id of record
     * @return $this
     */
    protected function checkRowIdInBackend($id)
    {
        $this->findElement($id, "//label[@for='Lists-checkbox-{$id}']")
             ->click();
        return $this;
    }

    /**
     * Type words into backend seach form
     * @param  string $value string to type in search form
     * @param  boolean $clear clear the searchbox or not
     * @return $this
     */
    protected function typeInBackendSearch($value='', $clear=false)
    {
        $this->type($value, 'listToolbarSearch[term]', $clear);
        return $this;
    }
}
