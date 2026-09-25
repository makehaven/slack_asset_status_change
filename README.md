# Slack Asset Status Change

Posts a short message to a tool's Slack channel (`field_item_slack_channel`,
else the area's `field_interest_slack_channel`) when the tool node's
`field_item_status` changes. Posts only on the Pantheon **live** environment;
dev, test and Lando log the message instead.

## What the channel hears

The tool channel is a member channel, so it hears **confirmed changes only**:

| Transition | Posted? |
|---|---|
| anything → Reported Concern (unconfirmed member report) | no |
| Reported Concern → Operational (report cleared, nothing was announced) | no |
| Reported Concern → Offline for Maintenance / Degraded (staff confirmed) | yes, as "*Tool* is now Offline for Maintenance." |
| Operational → Offline / Degraded / Gone / Storage | yes |
| Offline / Degraded → Operational | yes |

The weekly "still true?" reminder about stale statuses is **not** this module;
it comes from `asset_status`'s stale monitor and goes to the staff channel
(`asset_status.settings:stale_slack_channel`).

The decision lives in `slack_asset_status_change_should_post()`; its table is
covered by `tests/src/Unit/ShouldPostTest.php`.

## Configuration

Webhook URL from `slack_connector.settings:webhook_url`. No settings of its own.
A Rules action ("Send a status change message to Slack") is provided but not
used by any shipped rule.
