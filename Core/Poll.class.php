<?php
class Poll {
	public static function create(int $topicId, string $question, int $points, array $responses) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO polls(topic, question, points) VALUES(:topic, :question, :points)");
		$query->bindValue(":topic", $topicId, PDO::PARAM_INT);
		$query->bindValue(":question", $question, PDO::PARAM_STR);
		$query->bindValue(":points", $points, PDO::PARAM_INT);
		$query->execute();
		$pollId = $db->lastInsertId();
		
		foreach ($responses as $response) {
			$query = $db->prepare("INSERT INTO polls_responses(topic, response) VALUES(:topic, :response)");
			$query->bindValue(":topic", $topicId, PDO::PARAM_INT);
			$query->bindValue(":response", $response, PDO::PARAM_STR);
			$query->execute();
		}
		
		return $pollId;
	}
}