<?php
/* -------------------------------------------------------------------------- */
/* ::: ��Ƽ����                                                               */
/* -------------------------------------------------------------------------- */
$result_msg = "";

$r_res_cd         = $_POST[ "res_cd"         ];  // �����ڵ�
$r_res_msg        = $_POST[ "res_msg"        ];  // ���� �޽���
$r_cno            = $_POST[ "cno"            ];  // PG�ŷ���ȣ
$r_memb_id        = $_POST[ "memb_id"        ];  // ������ ID
$r_amount         = $_POST[ "amount"         ];  // �� �����ݾ�
$r_order_no       = $_POST[ "order_no"       ];  // �ֹ���ȣ
$r_noti_type      = $_POST[ "noti_type"      ];  // ��Ƽ���� ����(20), �Ա�(30), ����ũ�� ����(40)
$r_auth_no        = $_POST[ "auth_no"        ];  // ���ι�ȣ
$r_tran_date      = $_POST[ "tran_date"      ];  // �����Ͻ�
$r_card_no        = $_POST[ "card_no"        ];  // ī���ȣ
$r_issuer_cd      = $_POST[ "issuer_cd"      ];  // �߱޻��ڵ�
$r_issuer_nm      = $_POST[ "issuer_nm"      ];  // �߱޻��
$r_acquirer_cd    = $_POST[ "acquirer_cd"    ];  // ���Ի��ڵ�
$r_acquirer_nm    = $_POST[ "acquirer_nm"    ];  // ���Ի��
$r_install_period = $_POST[ "install_period" ];  // �Һΰ���
$r_noint          = $_POST[ "noint"          ];  // �����ڿ���
$r_bank_cd        = $_POST[ "bank_cd"        ];  // �����ڵ�
$r_bank_nm        = $_POST[ "bank_nm"        ];  // �����
$r_account_no     = $_POST[ "account_no"     ];  // ���¹�ȣ
$r_deposit_nm     = $_POST[ "deposit_nm"     ];  // �Ա��ڸ�
$r_expire_date    = $_POST[ "expire_date"    ];  // ���»�븸����
$r_cash_res_cd    = $_POST[ "cash_res_cd"    ];  // ���ݿ����� ����ڵ�
$r_cash_res_msg   = $_POST[ "cash_res_msg"   ];  // ���ݿ����� ����޽���
$r_cash_auth_no   = $_POST[ "cash_auth_no"   ];  // ���ݿ����� ���ι�ȣ
$r_cash_tran_date = $_POST[ "cash_tran_date" ];  // ���ݿ����� �����Ͻ�
$r_cp_cd          = $_POST[ "cp_cd"          ];  // ����Ʈ��
$r_used_pnt       = $_POST[ "used_pnt"       ];  // �������Ʈ
$r_remain_pnt     = $_POST[ "remain_pnt"     ];  // �ܿ��ѵ�
$r_pay_pnt        = $_POST[ "pay_pnt"        ];  // ����/�߻�����Ʈ
$r_accrue_pnt     = $_POST[ "accrue_pnt"     ];  // ��������Ʈ
$r_escrow_yn      = $_POST[ "escrow_yn"      ];  // ����ũ�� �������
$r_canc_date      = $_POST[ "canc_date"      ];  // ����Ͻ�
$r_canc_acq_date  = $_POST[ "canc_acq_date"  ];  // ��������Ͻ�
$r_refund_date    = $_POST[ "refund_date"    ];  // ȯ�ҿ����Ͻ�
$r_pay_type       = $_POST[ "pay_type"       ];  // ��������
$r_auth_cno       = $_POST[ "auth_cno"       ];  // �����ŷ���ȣ
$r_tlf_sno        = $_POST[ "tlf_sno"        ];  // ä���ŷ���ȣ
$r_account_type   = $_POST[ "account_type"   ];  // ä������ Ÿ�� US AN 1 (V-�Ϲ���, F-������)

/* -------------------------------------------------------------------------- */
/* ::: ��Ƽ���� - ����ũ�� ���º���                                           */
/* -------------------------------------------------------------------------- */
$r_escrow_yn      = $_POST[ "escrow_yn"      ];  // ����ũ������
$r_stat_cd        = $_POST[ "stat_cd "       ];  // ���濡��ũ�λ����ڵ�
$r_stat_msg       = $_POST[ "stat_msg"       ];  // ���濡��ũ�λ��¸޼���

if ( $r_res_cd == "0000" )
{
    /* ---------------------------------------------------------------------- */
    /* ::: ������ DB ó��                                                     */
    /* ---------------------------------------------------------------------- */
    /* DBó�� ���� �� : res_cd=0000, ���� �� : res_cd=5001                    */
    /* ---------------------------------------------------------------------- */
    if // DBó�� ���� ��
    {
        $result_msg = "res_cd=0000" . chr(31) . "res_msg=SUCCESS";
    }
    else // DBó�� ���� ��
    {
        $result_msg = "res_cd=5001" . chr(31) . "res_msg=FAIL";
    }
}

/* -------------------------------------------------------------------------- */
/* ::: ��Ƽ ó����� ó��                                                     */
/* -------------------------------------------------------------------------- */
echo $result_msg;
?>