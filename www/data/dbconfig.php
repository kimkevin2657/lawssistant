<?php
if(!defined('_MALLSET_')) exit;
define('MS_MYSQL_HOST', 'localhost');
define('MS_MYSQL_USER', 'lawssistant');
define('MS_MYSQL_PASSWORD', 'Sukim3344@');
define('MS_MYSQL_DB', 'lawssistant');
define('MS_MYSQL_SET_MODE', false);

define('G5_TABLE_PREFIX', 'g5_');
$g5['board_table'] = G5_TABLE_PREFIX.'board'; // �Խ��� ���� ���̺�

$g5['write_prefix'] = G5_TABLE_PREFIX.'write_'; // �Խ��� ���̺�� ���λ�


$g5['auth_table'] = G5_TABLE_PREFIX.'auth'; // �������� ���� ���̺�
$g5['config_table'] = G5_TABLE_PREFIX.'config'; // �⺻ȯ�� ���� ���̺�
$g5['group_table'] = G5_TABLE_PREFIX.'group'; // �Խ��� �׷� ���̺�
$g5['group_member_table'] = G5_TABLE_PREFIX.'group_member'; // �Խ��� �׷�+ȸ�� ���̺�
$g5['board_table'] = G5_TABLE_PREFIX.'board'; // �Խ��� ���� ���̺�
$g5['board_file_table'] = G5_TABLE_PREFIX.'board_file'; // �Խ��� ÷������ ���̺�
$g5['board_good_table'] = G5_TABLE_PREFIX.'board_good'; // �Խù� ��õ,����õ ���̺�
$g5['board_new_table'] = G5_TABLE_PREFIX.'board_new'; // �Խ��� ���� ���̺�
$g5['login_table'] = G5_TABLE_PREFIX.'login'; // �α��� ���̺� (�����ڼ�)
$g5['mail_table'] = G5_TABLE_PREFIX.'mail'; // ȸ������ ���̺�
$g5['member_table'] = G5_TABLE_PREFIX.'member'; // ȸ�� ���̺�
$g5['memo_table'] = G5_TABLE_PREFIX.'memo'; // �޸� ���̺�
$g5['poll_table'] = G5_TABLE_PREFIX.'poll'; // ��ǥ ���̺�
$g5['poll_etc_table'] = G5_TABLE_PREFIX.'poll_etc'; // ��ǥ ��Ÿ�ǰ� ���̺�
$g5['point_table'] = G5_TABLE_PREFIX.'point'; // ����Ʈ ���̺�
$g5['popular_table'] = G5_TABLE_PREFIX.'popular'; // �α�˻��� ���̺�
$g5['scrap_table'] = G5_TABLE_PREFIX.'scrap'; // �Խñ� ��ũ�� ���̺�
$g5['visit_table'] = G5_TABLE_PREFIX.'visit'; // �湮�� ���̺�
$g5['visit_sum_table'] = G5_TABLE_PREFIX.'visit_sum'; // �湮�� �հ� ���̺�
$g5['uniqid_table'] = G5_TABLE_PREFIX.'uniqid'; // ����ũ�� ���� ����� ���̺�
$g5['autosave_table'] = G5_TABLE_PREFIX.'autosave'; // �Խñ� �ۼ��� �����ð����� ���� �ӽ� �����ϴ� ���̺�
$g5['cert_history_table'] = G5_TABLE_PREFIX.'cert_history'; // �������� ���̺�
$g5['qa_config_table'] = G5_TABLE_PREFIX.'qa_config'; // 1:1���� �������̺�
$g5['qa_content_table'] = G5_TABLE_PREFIX.'qa_content'; // 1:1���� ���̺�
$g5['content_table'] = G5_TABLE_PREFIX.'content'; // ����(������)���� ���̺�
$g5['faq_table'] = G5_TABLE_PREFIX.'faq'; // �����Ͻô� ���� ���̺�
$g5['faq_master_table'] = G5_TABLE_PREFIX.'faq_master'; // �����Ͻô� ���� ������ ���̺�
$g5['new_win_table'] = G5_TABLE_PREFIX.'new_win'; // ��â ���̺�
$g5['menu_table'] = G5_TABLE_PREFIX.'menu'; // �޴����� ���̺�
$g5['social_profile_table'] = G5_TABLE_PREFIX.'member_social_profiles'; // �Ҽ� �α��� ���̺�


?>
