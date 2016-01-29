<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/6/2016
 * Time: 5:34 PM
 */
use Application\Core\Controller;
use MetzWeb\Instagram\Instagram;
use SessionHandler\Session\Session;
use Application\Model\Model_Media;
use Application\Model\Model_User;


class Controller_Main extends Controller
{
    public $config;
    public $instagram;

    public function __construct()
    {
        parent::__construct();
        $this->instagram_instance();
        $this->mediaModel = new Model_Media($this->dbh);
        $this->userModel = new Model_User($this->dbh);
    }

    public function action_index()
    {
        if (!Session::read('access_token')) {
            $result = array(
                'auth_user' => false,
                'login_url' => $this->instagram->getLoginUrl(),
                'id' => $this->instagram->getApiKey(),
                'apiCallBack' => $this->instagram->getApiCallback()
            );
            $this->view->generate('main_view.phtml', 'layout.phtml', $result);

            return true;
        }

        $this->instagram->setAccessToken(Session::read('access_token'));
        $inst_user_id = $this->mediaModel->get_user_id($this->instagram);
        if ($this->mediaModel->get_user_by_id($inst_user_id)) {
            $info = $this->update();
        } else {
            $info = $this->save();
        }
        $info['auth_user'] = true;
        $this->view->generate('user_data.phtml', 'layout.phtml', $info);

        return true;
    }

    public function action_social_login()
    {
        $token = Session::read('access_token');
        if (!$token) {
            Session::w('access_token', $_POST['token']);
        }
        echo true;
    }

    public function action_login_checking()
    {
        if (intval(isset($_GET['error']))) {
            echo "<script>self.close()</script>";
        }
    }


    public function update()
    {
        $data = $this->mediaModel->get_user_media($this->instagram);
        $result = $this->mediaModel->update($data);
        if ($result) {
            return $data;
        }

        return false;
    }

    public function save()
    {
        $data = $this->mediaModel->get_user_media($this->instagram);
        $result = $this->mediaModel->save($data);
        if ($result) {
            return $data;
        }

        return false;
    }

    public function action_update_media()
    {
        $this->instagram->setAccessToken(Session::read('access_token'));
        $info = $this->update();
        $images = array();
        $i = 0;
        foreach ($info['userMedia']->data as $data) {
            $images[$i]['url'] = $data->images->standard_resolution->url;
            $images[$i]['likes'] = $data->likes->count;
            $images[$i]['comments'] = $data->comments->count;
            $i++;
        }
        echo json_encode(array(
            'images' => $images
        ));
    }

    private function instagram_instance()
    {
        $config = $this->helper->config;
        $this->instagram = new Instagram(array(
            'apiKey' => $config['instagram']['clientKey'],
            'apiSecret' => $config['instagram']['clientId'],
            'apiCallback' => $config['instagram']['apiCallBack'],
        ));

        return true;
    }
}