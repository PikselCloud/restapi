<?php

require_once('db/class.RequestStore.php');

/**
 * Requests store
 *
 * @access public
 * @author Olivier Godon <olivier.godon@kit-digital.com>
 */
class KWGChannelRequestStore
	extends RequestStore
{
	
	public function getChannelVideos($csig) {
		return "SELECT P.name as channel_name, P.creation_date as channel_creation_date, I.sig, username, I.creation_date as video_creation_date,
I.language_code, duration, LT1.text_value as title, LT2.text_value as description
FROM KEWEGO.PLAYLIST P
LEFT JOIN KEWEGO.PLAYLIST_ITEMS PI ON PI.playlist_id=P.playlist_id
INNER JOIN KEWEGO.ITEM I ON I.id=PI.item_id
INNER JOIN KEWEGO.LOCALIZED_TEXT LT1 ON LT1.id=I.localized_title_id
INNER JOIN KEWEGO.LOCALIZED_TEXT LT2 ON LT2.id=I.localized_description_id
INNER JOIN KEWEGO.KEWEGO_USER KU ON KU.id=I.creation_user_id
WHERE P.deleted='no' AND P.sig='".$this->escape($csig)."' AND I.deleted='no'";
	}
	
}
