<?php

namespace GovInfo\Requestor;

final class PublishedAbstractRequestor extends AbstractRequestor
{
    private $strStartDate = '';
    private $strEndDate = '';
    private $strCollectionCode = '';
    private $strCongress = '';
    private $strDocClass = '';
    private $strModifiedSince = '';

    public function setStrCollectionCode(string $strCollectionCode) : self
    {
        $this->strCollectionCode = $strCollectionCode;
        return $this;
    }

    public function setStrStartDate(string $strStartDate) : self
    {
        $this->strStartDate = $strStartDate;
        return $this;
    }

    public function setStrEndDate(string $strEndDate) : self
    {
        $this->strEndDate = $strEndDate;
        return $this;
    }

    public function setStrDocClass(string $strDocClass) : self
    {
        $this->strDocClass = $strDocClass;
        return $this;
    }

    public function setStrCongress(string $strCongress) : self
    {
        $this->strCongress = $strCongress;
        return $this;
    }

    public function setStrModifiedSince(string $strModifiedSince) : self
    {
        $this->strModifiedSince = $strModifiedSince;
        return $this;
    }    

    public function getStrCollectionCode() : string
    {
        return $this->strCollectionCode;
    }

    public function getStrStartDate()
    {
        return $this->strStartDate;
    }

    public function getStrEndDate()
    {
        return $this->strEndDate;
    }

    public function getStrDocClass() : string
    {
        return $this->strDocClass;
    }

    public function getStrCongress() : string
    {
        return $this->strCongress;
    }

    public function getStrModifiedSince() : string
    {
        return $this->strModifiedSince;
    } 
}