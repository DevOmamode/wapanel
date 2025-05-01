// vite.config.js
import { defineConfig } from "file:///C:/xampp/htdocs/wapanel/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/xampp/htdocs/wapanel/node_modules/laravel-vite-plugin/dist/index.mjs";
import i18n from "file:///C:/xampp/htdocs/wapanel/node_modules/laravel-vue-i18n/dist/vite.mjs";
import VueI18nPlugin from "file:///C:/xampp/htdocs/wapanel/node_modules/@intlify/unplugin-vue-i18n/lib/vite.mjs";
import vue from "file:///C:/xampp/htdocs/wapanel/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import path from "path";
var __vite_injected_original_dirname = "C:\\xampp\\htdocs\\wapanel";
var vite_config_default = defineConfig({
  plugins: [
    vue(),
    VueI18nPlugin({
      // Define the path to your locale files
      include: path.resolve(__vite_injected_original_dirname, "lang/**")
    }),
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true
    }),
    i18n()
  ],
  resolve: {
    alias: {
      "@modules": path.resolve(__vite_injected_original_dirname, "./modules")
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFx4YW1wcFxcXFxodGRvY3NcXFxcd2FwYW5lbFwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxceGFtcHBcXFxcaHRkb2NzXFxcXHdhcGFuZWxcXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L3hhbXBwL2h0ZG9jcy93YXBhbmVsL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XHJcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nO1xyXG5pbXBvcnQgaTE4biBmcm9tICdsYXJhdmVsLXZ1ZS1pMThuL3ZpdGUnOyBcclxuaW1wb3J0IFZ1ZUkxOG5QbHVnaW4gZnJvbSAnQGludGxpZnkvdW5wbHVnaW4tdnVlLWkxOG4vdml0ZSc7XHJcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJ1xyXG5pbXBvcnQgcGF0aCBmcm9tICdwYXRoJztcclxuXHJcbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XHJcbiAgICBwbHVnaW5zOiBbXHJcbiAgICAgICAgdnVlKCksXHJcbiAgICAgICAgVnVlSTE4blBsdWdpbih7XHJcbiAgICAgICAgICAgIC8vIERlZmluZSB0aGUgcGF0aCB0byB5b3VyIGxvY2FsZSBmaWxlc1xyXG4gICAgICAgICAgICBpbmNsdWRlOiBwYXRoLnJlc29sdmUoX19kaXJuYW1lLCAnbGFuZy8qKicpXHJcbiAgICAgICAgfSksXHJcbiAgICAgICAgbGFyYXZlbCh7XHJcbiAgICAgICAgICAgIGlucHV0OiBbJ3Jlc291cmNlcy9jc3MvYXBwLmNzcycsICdyZXNvdXJjZXMvanMvYXBwLmpzJ10sXHJcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXHJcbiAgICAgICAgfSksXHJcbiAgICAgICAgaTE4bigpLFxyXG4gICAgXSxcclxuICAgIHJlc29sdmU6IHtcclxuICAgICAgICBhbGlhczoge1xyXG4gICAgICAgICAgJ0Btb2R1bGVzJzogcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgJy4vbW9kdWxlcycpLFxyXG4gICAgICAgIH0sXHJcbiAgICB9LFxyXG59KTsiXSwKICAibWFwcGluZ3MiOiAiO0FBQStQLFNBQVMsb0JBQW9CO0FBQzVSLE9BQU8sYUFBYTtBQUNwQixPQUFPLFVBQVU7QUFDakIsT0FBTyxtQkFBbUI7QUFDMUIsT0FBTyxTQUFTO0FBQ2hCLE9BQU8sVUFBVTtBQUxqQixJQUFNLG1DQUFtQztBQU96QyxJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixTQUFTO0FBQUEsSUFDTCxJQUFJO0FBQUEsSUFDSixjQUFjO0FBQUE7QUFBQSxNQUVWLFNBQVMsS0FBSyxRQUFRLGtDQUFXLFNBQVM7QUFBQSxJQUM5QyxDQUFDO0FBQUEsSUFDRCxRQUFRO0FBQUEsTUFDSixPQUFPLENBQUMseUJBQXlCLHFCQUFxQjtBQUFBLE1BQ3RELFNBQVM7QUFBQSxJQUNiLENBQUM7QUFBQSxJQUNELEtBQUs7QUFBQSxFQUNUO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxPQUFPO0FBQUEsTUFDTCxZQUFZLEtBQUssUUFBUSxrQ0FBVyxXQUFXO0FBQUEsSUFDakQ7QUFBQSxFQUNKO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
