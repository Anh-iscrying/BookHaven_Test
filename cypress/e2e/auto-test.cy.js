// cypress/e2e/registration.cy.js (Hoặc auto-test.cy.js)

describe('BookHaven - Registration Functionality', () => {
  // KHAI BÁO BIẾN Ở CẤP ĐỘ DESCRIBE
  const registerPageUrl = 'http://localhost/Web_ban_sach_-ACS/DK.php';
  const loginPageUrl = 'http://localhost/Web_ban_sach_-ACS/DN.php';

  beforeEach(() => {
    cy.visit(registerPageUrl);
  });

  // --- Test Cases cho Client-side Validation (JavaScript) ---
  it('TC_REG_CLIENT_INVALID_EMAIL_FORMAT: Should show client-side error for invalid email format (@gmail.com)', () => {
    cy.get('input#name').type('Test Client Email');
    cy.get('input#email').type('test@notgmail.com'); // Email sai định dạng client
    cy.get('input#phone').type('0123456789');
    cy.get('input#password').type('ValidPassword1'); // Mật khẩu đủ mạnh
    cy.get('input#confirmPassword').type('ValidPassword1');
    
    // Click nút submit để trigger onsubmit và hàm validateForm()
    cy.get('button.buttondn[name="register"]').click(); 

    cy.get('div#email-error')
      .should('be.visible')
      .and('contain.text', 'Email không hợp lệ (phải là @gmail.com).');
    cy.url().should('eq', registerPageUrl); // Đảm bảo vẫn ở trang đăng ký
  });

  it('TC_REG_CLIENT_INVALID_PHONE_FORMAT: Should show client-side error for invalid phone format', () => {
    cy.get('input#name').type('Test Client Phone');
    cy.get('input#email').type(`test_phone_${Date.now()}@gmail.com`);
    cy.get('input#phone').type('1234567890'); // Sai định dạng SĐT client
    cy.get('input#password').type('ValidPassword1');
    cy.get('input#confirmPassword').type('ValidPassword1');
    
    cy.get('button.buttondn[name="register"]').click(); // Click nút submit

    cy.get('div#phone-error')
      .should('be.visible')
      .and('contain.text', 'Số điện thoại không hợp lệ (phải bắt đầu bằng 0 và có 10 chữ số).');
    cy.url().should('eq', registerPageUrl); // Đảm bảo vẫn ở trang đăng ký
  });

  it('TC_REG_CLIENT_PASSWORD_MISMATCH: Should show client-side alert if passwords do not match on submit', () => {
    const stub = cy.stub();
    cy.on('window:alert', stub);

    cy.get('input#name').type('Test User Mismatch');
    cy.get('input#email').type(`test_mismatch_${Date.now()}@gmail.com`);
    cy.get('input#phone').type('0987654321');
    cy.get('input#password').type('Password123');
    cy.get('input#confirmPassword').type('Password456'); // Mật khẩu không khớp
    
    cy.get('button.buttondn[name="register"]').click().then(() => {
      // Kiểm tra alert được gọi với đúng nội dung
      // Do validateForm() trả về false, form không submit, alert là của client
      expect(stub.getCall(0)).to.be.calledWith('Mật khẩu và nhập lại mật khẩu không khớp!');
    });
    cy.url().should('eq', registerPageUrl); // Vẫn ở trang đăng ký
  });

  // --- Test Cases cho Server-side Validation (PHP) và Đăng ký thành công ---
  it('TC_REG_SERVER_VALID_001: Should register successfully and redirect to login page with valid data', () => {
    const uniqueEmail = `test_success_${Date.now()}@gmail.com`;
    cy.get('input#name').type('Valid Server User');
    cy.get('input#email').type(uniqueEmail);
    cy.get('input#phone').type('0123456789');
    // Mật khẩu đáp ứng yêu cầu PHP: ít nhất 8 ký tự, có chữ hoa, có số
    cy.get('input#password').type('ValidPass123'); 
    cy.get('input#confirmPassword').type('ValidPass123');

    cy.get('button.buttondn[name="register"]').click();

    // Kiểm tra chuyển hướng đến trang DN.php
    cy.url().should('eq', loginPageUrl);
  });

  it('TC_REG_SERVER_EMAIL_EXISTS: Should show server-side error (text on page) if email already exists', () => {
    const existingEmail = `exist_server_${Date.now()}@gmail.com`;

    // Đăng ký lần 1 (mong đợi thành công)
    cy.get('input#name').type('First User For Email Exist Test');
    cy.get('input#email').type(existingEmail);
    cy.get('input#phone').type('0900000011');
    cy.get('input#password').type('ValidPassword123');
    cy.get('input#confirmPassword').type('ValidPassword123');
    cy.get('button.buttondn[name="register"]').click();
    cy.url().should('eq', loginPageUrl); // Đảm bảo đã chuyển hướng

    cy.visit(registerPageUrl); // Quay lại trang đăng ký

    // Đăng ký lần 2 với cùng email
    cy.get('input#name').type('Second User For Email Exist Test');
    cy.get('input#email').type(existingEmail); // Dùng lại email
    cy.get('input#phone').type('0900000012');
    cy.get('input#password').type('AnotherValid123');
    cy.get('input#confirmPassword').type('AnotherValid123');
    cy.get('button.buttondn[name="register"]').click();

    // Giả định PHP đã được sửa để exit() sau khi echo lỗi SQL
    cy.contains('Có lỗi xảy ra: Duplicate entry').should('be.visible');
    cy.url().should('eq', registerPageUrl); // Vẫn ở trang đăng ký
  });

  it('TC_REG_SERVER_WEAK_PASSWORD: Should show server-side alert for weak password', () => {
    const stub = cy.stub();
    cy.on('window:alert', stub);

    cy.get('input#name').type('Weak Pass Server');
    cy.get('input#email').type(`weakpass_server_${Date.now()}@gmail.com`);
    cy.get('input#phone').type('0123456780');
    cy.get('input#password').type('weak'); // Mật khẩu không đủ mạnh theo PHP
    cy.get('input#confirmPassword').type('weak');
    
    cy.get('button.buttondn[name="register"]').click().then(() => {
      // Giả định PHP sẽ alert lỗi này và exit
      expect(stub.getCall(0)).to.be.calledWith('Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa và số!');
    });
    cy.url().should('eq', registerPageUrl); // Vẫn ở trang đăng ký (do PHP alert và exit)
  });

  it('TC_REG_SERVER_INVALID_PHONE_PHP: Should show server-side alert for invalid phone (PHP check)', () => {
    const stub = cy.stub();
    cy.on('window:alert', stub);

    cy.get('input#name').type('Invalid Phone Server');
    cy.get('input#email').type(`invalidphone_server_${Date.now()}@gmail.com`);
    cy.get('input#phone').type('12345'); // Số điện thoại không hợp lệ
    cy.get('input#password').type('ValidPassword123');
    cy.get('input#confirmPassword').type('ValidPassword123');
    
    cy.get('button.buttondn[name="register"]').click().then(() => {
      // Giả định PHP sẽ alert lỗi này và exit
      expect(stub.getCall(0)).to.be.calledWith('Số điện thoại không hợp lệ (phải bắt đầu bằng 0 và có 10 chữ số)!');
    });
    cy.url().should('eq', registerPageUrl); // Vẫn ở trang đăng ký (do PHP alert và exit)
  });
});