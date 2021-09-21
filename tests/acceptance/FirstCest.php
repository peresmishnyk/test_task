<?php

class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('MyTower PHP test');
    }

    public function showAllWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/all');
        $I->see('All Prepared Dataset');
    }
}
