<?php

namespace GovInfo;

use GuzzleHttp\Psr7\Uri;
use GovInfo\Requestor\PublishedAbstractRequestor;
use LogicException;

final class Published
{
  use EndpointTrait;

  private const ENDPOINT = 'published';

  /**
   * Constructs an instance
   * 
   * @param Api $objApi
   */
  public function __construct(Api $objApi)
  {
    $this->objApi = $objApi;
  }

  /**
   * Returns a type of published
   *
   * @param PublishedAbstractRequestor $objRequestor
   * @return array
   * @throws LogicException
   * @throws RunTimeException
   */
  public function item(publishedAbstractRequestor $objRequestor) : array
  {
    if (empty($objRequestor->getStrCollectionCode())) {
      throw new LogicException('PublishedRequestor::strCollectionCode is required');
    }

    $objUri = new Uri();
    $objUri = $objUri->withQueryValue($objUri, 'pageSize', $objRequestor->getIntPageSize());
    $objUri = $objUri->withQueryValue($objUri, 'offset', $objRequestor->getIntOffSet());
    $objUri = $objUri->withQueryValue($objUri, 'collection', $objRequestor->getStrCollectionCode());
    $objUri = $objUri->withQueryValue($objUri, 'docClass', $objRequestor->getStrDocClass());

    $strStartDate = $objRequestor->getStrStartDate();
    $strEndDate = $objRequestor->getStrEndDate();

    if (empty($strStartDate)) {
      throw new RunTimeException('Start Date is required');
    }

    if (empty($strEndDate)) {
      $strPath = self::ENDPOINT . '/' . $strStartDate;
    }
    else {
      $strPath = self::ENDPOINT . '/' . $strStartDate . '/' . $strEndDate;
    }

    $objResult = $this->objApi->getArray($objUri->withPath($strPath));

    return $objResult;
  }

  /**
   * Validate the date from our input
   * https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
   */
  private function validateDate($date, $format = 'Y-m-d') {
    $d = \DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of 
    // digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
  }
}