<?php
//���������ļ�
require_once('Model.php');

/**
 * ���ݿ��������
 * @return \mysqli
 */
$_config = array(
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'videorentalsystem',
    'DB_USER' => 'root',
    'DB_PWD' => 'root',
    'DB_PORT' => '3306',
);
function M() {
    $db = new Model();
    if (mysqli_connect_errno())
        die("����ʧ��: " . mysqli_connect_error());
    return $db;
}
// ��ȡ����ֵ
function C($name = null) {
    //��̬ȫ�ֱ����������ʹ��ȡֵ������ $_config����ȡ
    global $_config;
    // �޲���ʱ��ȡ����
    if (empty($name))
        return $_config;
    // ����ִ�����û�ȡ��ֵ
    if (is_string($name)) {
        return $_config[$name];
    }

}
function ajaxReturn($data = null, $message = "", $status) {
    $ret = array();
    $ret["data"] = $data;
    $ret["message"] = $message;
    $ret["status"] = $status;
    echo json_encode($ret);
    die();
}

?>
