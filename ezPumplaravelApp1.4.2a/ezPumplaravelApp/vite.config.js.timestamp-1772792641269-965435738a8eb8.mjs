// vite.config.js
import { defineConfig } from "file:///D:/laragon/www/ezPumplaravelApp/node_modules/vite/dist/node/index.js";
import laravel from "file:///D:/laragon/www/ezPumplaravelApp/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///D:/laragon/www/ezPumplaravelApp/node_modules/@vitejs/plugin-vue/dist/index.mjs";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/sass/app.scss",
        "resources/js/app.js"
      ],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js"
    }
  }
  // server: {
  //     host: "10.10.16.103:8000", // <-- your LAN IP
  //     port: 8000,           // <-- custom port
  //     hmr: {
  //         host: "10.10.16.103:8000", // Hot Module Replacement host
  //     }
  // }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJEOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFxlelB1bXBsYXJhdmVsQXBwXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJEOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFxlelB1bXBsYXJhdmVsQXBwXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9EOi9sYXJhZ29uL3d3dy9lelB1bXBsYXJhdmVsQXBwL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6IFtcbiAgICAgICAgICAgICAgICAncmVzb3VyY2VzL3Nhc3MvYXBwLnNjc3MnLFxuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvanMvYXBwLmpzJyxcbiAgICAgICAgICAgIF0sXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICB9KSxcbiAgICAgICAgdnVlKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiB7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtQXNzZXRVcmxzOiB7XG4gICAgICAgICAgICAgICAgICAgIGJhc2U6IG51bGwsXG4gICAgICAgICAgICAgICAgICAgIGluY2x1ZGVBYnNvbHV0ZTogZmFsc2UsXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0pLFxuICAgIF0sXG4gICAgcmVzb2x2ZToge1xuICAgICAgICBhbGlhczoge1xuICAgICAgICAgICAgdnVlOiAndnVlL2Rpc3QvdnVlLmVzbS1idW5kbGVyLmpzJyxcbiAgICAgICAgfSxcblxuICAgIH0sXG4gICAgLy8gc2VydmVyOiB7XG4gICAgLy8gICAgIGhvc3Q6IFwiMTAuMTAuMTYuMTAzOjgwMDBcIiwgLy8gPC0tIHlvdXIgTEFOIElQXG4gICAgLy8gICAgIHBvcnQ6IDgwMDAsICAgICAgICAgICAvLyA8LS0gY3VzdG9tIHBvcnRcbiAgICAvLyAgICAgaG1yOiB7XG4gICAgLy8gICAgICAgICBob3N0OiBcIjEwLjEwLjE2LjEwMzo4MDAwXCIsIC8vIEhvdCBNb2R1bGUgUmVwbGFjZW1lbnQgaG9zdFxuICAgIC8vICAgICB9XG4gICAgLy8gfVxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQXVSLFNBQVMsb0JBQW9CO0FBQ3BULE9BQU8sYUFBYTtBQUNwQixPQUFPLFNBQVM7QUFFaEIsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsU0FBUztBQUFBLElBQ0wsUUFBUTtBQUFBLE1BQ0osT0FBTztBQUFBLFFBQ0g7QUFBQSxRQUNBO0FBQUEsTUFDSjtBQUFBLE1BQ0EsU0FBUztBQUFBLElBQ2IsQ0FBQztBQUFBLElBQ0QsSUFBSTtBQUFBLE1BQ0EsVUFBVTtBQUFBLFFBQ04sb0JBQW9CO0FBQUEsVUFDaEIsTUFBTTtBQUFBLFVBQ04saUJBQWlCO0FBQUEsUUFDckI7QUFBQSxNQUNKO0FBQUEsSUFDSixDQUFDO0FBQUEsRUFDTDtBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ0wsT0FBTztBQUFBLE1BQ0gsS0FBSztBQUFBLElBQ1Q7QUFBQSxFQUVKO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFRSixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
