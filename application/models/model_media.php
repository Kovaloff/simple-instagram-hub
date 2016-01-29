<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/23/2016
 * Time: 11:41 AM
 */
namespace Application\Model;

use Application\Core\Model;
use MetzWeb\Instagram\Instagram;

class Model_Media extends Model
{

    public function save($media)
    {
        $sql = $this->dbh->prepare('INSERT INTO media_info (userId, username, likes,comments) VALUES(:userId,:username, :likes,:comments)');
        $sql->bindParam(':userId', $media['userData']->data->id);
        $sql->bindParam(':username', $media['userData']->data->username);
        $sql->bindParam(':likes', $media['userLikes']);
        $sql->bindParam(':comments', $media['userComments']);

        $result = $sql->execute();
        if ($result) {
            return true;
        }

        return false;
    }

    public function update($media)
    {
        $sql = $this->dbh->prepare("UPDATE media_info SET userId = ?, username = ?, likes = ?, comments = ?, date = NOW() WHERE username='{$media['userData']->data->username}'");

        $result = $sql->execute(array(
            $media['userData']->data->id,
            $media['userData']->data->username,
            $media['userLikes'],
            $media['userComments']
        ));
        if ($result) {
            return true;
        }

        return false;
    }

    protected function fetch_all_by_user_id($userId)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM media_info WHERE username LIKE ?");
        $stmt->bindValue(1, $userId, \PDO::PARAM_STR);
        $result = $stmt->execute();
        if ($result) {
            return $result;
        }

        return false;
    }

    function shouldUpdate($userId){
        $stmt = $this->dbh->prepare("SELECT date FROM media_info WHERE userId LIKE ?");
        $stmt->execute(array($userId));
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $start_date = strtotime($result['date']);
        $now = time();

        if ($result && ($now - intval($start_date)) >= (2.5*60)){
            return true;
        } else {
            return false;
        }
    }
    public function get_user_by_id($uid)
    {
        $stmt = $this->dbh->prepare("SELECT userId FROM media_info WHERE userId LIKE ?");
        $stmt->execute(array($uid));
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            return $result['userId'];
        }

        return false;
    }


    function get_user_media(Instagram $instagram)
    {
        $userData = $instagram->getUser();
        $userMedia = $instagram->getUserMedia('self');
        $userLikes = $instagram->getUserLikes($userMedia);
        $userComments = $instagram->getUserComments($userMedia);
        $userTags = $instagram->getUserTags($userMedia);

        $data = array(
            'userData' => $userData,
            'userMedia' => $userMedia,
            'userLikes' => $userLikes,
            'userComments' => $userComments,
            'userTags' => $userTags,
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    function get_user_id(Instagram $instagram)
    {
        $userData = $instagram->getUser();

        if ($userData) {
            return $userData->data->id;
        }

        return false;
    }

}