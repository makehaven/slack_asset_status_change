<?php

namespace Drupal\Tests\slack_asset_status_change\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Decision table for which status transitions reach the tool channel.
 *
 * @group slack_asset_status_change
 */
class ShouldPostTest extends TestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    require_once __DIR__ . '/../../../slack_asset_status_change.module';
  }

  /**
   * Checks one transition against the decision table.
   *
   * @dataProvider transitions
   */
  public function testShouldPost(?string $old, ?string $new, bool $expected, string $why): void {
    $this->assertSame($expected, slack_asset_status_change_should_post($old, $new), $why);
  }

  /**
   * Transitions seen on live in the last 12 months, plus edge cases.
   */
  public static function transitions(): array {
    return [
      ['Operational', 'Reported Concern', FALSE, 'unconfirmed member report is not announced'],
      ['Offline for Maintenance', 'Reported Concern', FALSE, 'a new report on a tool already down is not announced'],
      ['Reported Concern', 'Operational', FALSE, 'clearing an unannounced report announces nothing'],
      ['Reported Concern', 'Offline for Maintenance', TRUE, 'staff confirmed the tool is down'],
      ['Reported Concern', 'Degraded', TRUE, 'staff confirmed a degraded tool'],
      ['Operational', 'Offline for Maintenance', TRUE, 'staff took the tool down'],
      ['Operational', 'Degraded', TRUE, 'staff marked the tool degraded'],
      ['Offline for Maintenance', 'Operational', TRUE, 'tool is back'],
      ['Degraded', 'Operational', TRUE, 'tool is back'],
      ['Offline for Maintenance', 'Gone', TRUE, 'tool retired'],
      ['Operational', 'Gone', TRUE, 'tool retired'],
      ['Operational', 'Storage', TRUE, 'tool put in storage'],
      ['Offline - Initial Setup', 'Operational', TRUE, 'new tool goes live'],
      ['', 'Operational', TRUE, 'no previous status still announces the new one'],
      [NULL, 'Operational', TRUE, 'NULL previous status still announces the new one'],
      ['Operational', 'Operational', FALSE, 'no change'],
      ['Operational', '', FALSE, 'unknown new status'],
      ['Operational', NULL, FALSE, 'NULL new status'],
    ];
  }

}
