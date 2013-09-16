<?php

require_once('utils/class.Timer.php');

class KWGChannel {
	
	public function getVideos($requestVO) {
		$timer = Timer::getInstance();
		$timer->tStart();
		$cacheKey = 'KWG_CHANNEL_GET_VIDEOS'.$requestVO->channel;
		$videosList = apc_fetch($cacheKey);
		$duration = 0;
		if($videosList === FALSE) {
			require_once('db/class.MySQLDB.php');
			$db = new MySQLDB();
			$db->connect('dbd0.cardinet.kewego.int', 'root', 'pulse', 'KEWEGO');
			require_once('class.KWGChannelRequestStore.php');
			$rs = new KWGChannelRequestStore($db);
			$resultVO = $db->query($rs->getChannelVideos($requestVO->channel));
			$videosList = $resultVO->getResult();
			$duration = $resultVO->getDuration();
			apc_store($cacheKey, $videosList, CHANNEL_VIDEOS_CACHE_DURATION);
		}
		// CSIG : iLyROoafYz47
		if(count($videosList) === 0) {
			require_once('exception/class.WebServiceException.php');
			throw new WebServiceException(500, "The channel signature '".$requestVO->channel."' is not valid.");
		}
		$channelTitle = NULL;
		$videos = array();
		foreach($videosList as $video) {
			if($channelTitle === NULL) {
				$channelTitle = $video['channel_name'];
			}
			$video = array(
				'title' => $video['title'],
				'sig' => $video['sig'],
				'language_code' => $video['language_code'],
				'creation_date' => $video['video_creation_date'],
				'description' => $video['description'],
				'duration' => $video['duration']
			);
			$videos[] = $video;
		}
		require_once('vo/class.ChannelVO.php');
		$channelVO = new ChannelVO(array(
			'title' => $channelTitle,
			'items' => $videos
		));
		$methodDuration = $timer->tFinal();
		$channelVO->addDuration($duration, 'query');
		$channelVO->addDuration($methodDuration, "wrapperMethod");
		return $channelVO;
	}
	
}
