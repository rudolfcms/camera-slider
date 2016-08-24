<?php

namespace mklj\Rudolf\Plugin\CameraSlider;

use Rudolf\Framework\Model\FrontModel;

class Model extends FrontModel
{
    public function getItems($count)
    {
        $stmt = $this->pdo->prepare("SELECT id, title, thumb, album, date, slug FROM {$this->prefix}articles
			WHERE published = 1 AND thumb != '' AND album != '' ORDER BY id DESC LIMIT 0, :count"
        );
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();

        if (empty($results)) {
            return false;
        }

        return $results;
    }
}
