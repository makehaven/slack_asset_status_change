<?php

namespace Drupal\slack_asset_status_change\Plugin\RulesAction;

use Drupal\rules\Core\RulesActionBase;
use Drupal\node\NodeInterface;

/**
 * Provides a 'Send a status change message to Slack' action.
 *
 * @RulesAction(
 *   id = "slack_asset_status_change_send_message",
 *   label = @Translation("Send a status change message to Slack"),
 *   category = @Translation("Custom")
 * )
 */
class SlackAssetStatusChangeSendMessage extends RulesActionBase {

  /**
   * Executes the action.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node whose status changed.
   */
  public function execute($node = NULL) {
    if ($node instanceof NodeInterface) {
      slack_asset_status_change_send_message_from_node($node);
    }
  }
}
