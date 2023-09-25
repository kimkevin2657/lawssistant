<style>
    .tbl_wrap td th { padding:5px; }
</style>
<table>
    <thead>
    <tr>
        <th>수집 및 동의목적</th>
        <th>수집 항목</th>
        <th>보유 및 이용기간</th>
    </tr>
    </thead>
    <tbody>
    <?php if( false ) : ?>
        <tr>
            <td>이용자 식별 및 본인여부 확인</td>
            <td>아이디, 이름, 비밀번호</td>
            <td>회원 탈퇴 시까지</td>
        </tr>
        <tr>
            <td>고객서비스 이용에 관한 통지, CS대응을 위한 이용자 식별</td>
            <td>연락처 (이메일, 휴대전화번호)</td>
            <td>회원 탈퇴 시까지</td>
        </tr>
    <?php else : ?>
        <tr>
            <td>쇼핑몰 회원가입 및 상품구매</td>
            <td>성명, 전화번호, 휴대전화번호, 자택주소, 고유식별번호(주민등록번호와 생년월일)</td>
            <td>수집, 이용 동의일로 부터 계약의 효력이종료시까지(환불 및 해지)</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>