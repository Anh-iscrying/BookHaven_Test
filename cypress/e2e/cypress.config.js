// cypress.config.js
const { defineConfig } = require('cypress')

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://localhost/Web_ban_sach_-ACS', // Bỏ /index.php ở đây
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    // Bạn có thể thêm các cấu hình khác ở đây nếu cần
    // Ví dụ: viewportWidth, viewportHeight
    // viewportWidth: 1280,
    // viewportHeight: 720,
  },
})