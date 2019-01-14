<?php
/**
 * ���ݿ�ģ����
 */
require_once 'common.php';
class Model {
 // ��ǰ���ݿ��������
 protected $db = null;
 // ��ǰ��ѯID
 protected $queryID = null;
 // ��ǰSQLָ��
 protected $queryStr = '';
 // �Ƿ��Ѿ��������ݿ�
 protected $connected = false;
 // ���ػ���Ӱ���¼��
 protected $numRows = 0;
 // �����ֶ���
 protected $numCols = 0;
 // ���������Ϣ
 protected $error = '';
 public function __construct() {
  $this->db = $this->connect();//�������ݿ����Ϊ���ݿ����Ӷ���
 }
 /**
  * �������ݿⷽ��
  */
 public function connect() {
    $config = array(
     'username' => C('DB_USER'),
     'password' => C('DB_PWD'),
     'hostname' => C('DB_HOST'),
     'hostport' => C('DB_PORT'),
     'database' => C('DB_NAME')
    );
   $link = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
   if (mysqli_connect_errno())
       die("����ʧ��: " . mysqli_connect_error());
   $this->connected = true;
   //�������ݿ����Ӷ���
   return $link;
  }
  
 /**
  * ��ʼ�����ݿ�����
  */
 public function query($sql){
     return $this->db->query($sql);
 }
 protected function initConnect() {
  if (!$this->connected) {
   $this->db = $this->connect();
  }
 }
 /**
  * ������еĲ�ѯ����
  * @access private
  * @param string $sql sql���
  * @return array
  */
 public function select($sql) {
  $this->initConnect();
  if (!$this->db)
   return false;
  $query = $this->db->query($sql);
  $list = array();
  if (!$query)
   return $list;
  while ($rows = $query->fetch_assoc()) {
   $list[] = $rows;
  }
  return $list;
 }
 /**
  * ֻ��ѯһ������
  */
 public function find($sql) {
  $resultSet = $this->select($sql);
  if (false === $resultSet) {
   return false;
  }
  if (empty($resultSet)) {// ��ѯ���Ϊ��
   return null;
  }
  $data = $resultSet[0];
  return $data;
 }
 /**
  * ��ȡһ����¼��ĳ���ֶ�ֵ , sql ���Լ���֯
  * ���ӣ� $model->getField("select id from user limit 1")
  */
 public function getField($sql) {
  $resultSet = $this->select($sql);
  if (!empty($resultSet)) {
   return reset($resultSet[0]);
  }
 }
 /**
  * ִ����� �� ������룬���²���
  * @access public
  * @param string $str sqlָ��
  * @return integer
  */
 public function execute($str) {
  $this->initConnect();
  if (!$this->db)
   return false;
  $this->queryStr = $str;
  //�ͷ�ǰ�εĲ�ѯ���
  if ($this->queryID)
   $this->free();
  $result = $this->db->query($str);
  if (false === $result) {
   $this->error();
   return false;
  } else {
   $this->numRows = $this->db->affected_rows;
   $this->lastInsID = $this->db->insert_id;
   return $this->numRows;
  }
 }
 /**
  * ������еĲ�ѯ����
  * @access private
  * @param string $sql sql���
  * @return array
  */
 private function getAll() {
  //�������ݼ�
  $result = array();
  if ($this->numRows > 0) {
   //�������ݼ�
   for ($i = 0; $i < $this->numRows; $i++) {
    $result[$i] = $this->queryID->fetch_assoc();
   }
   $this->queryID->data_seek(0);
  }
  return $result;
 }
 /**
  * �����������ID
  */
 public function getLastInsID() {
  return $this->db->insert_id;
 }
 /**
  * �ͷŲ�ѯ���
  */
 public function free() {
  $this->queryID->free_result();
  $this->queryID = null;
 }
 /**
  * �ر����ݿ�
  */
 public function close() {
  if ($this->db) {
   $this->db->close();
  }
  $this->db = null;
 }
 /**
  * ��������
  */
 public function __destruct() {
  if ($this->queryID) {
   $this->free();
  }
  // �ر�����
  $this->close();
 }
}
