import {defineConfig} from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    base: '',
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './src'),
            '@store': path.resolve(__dirname, './src/store'),
            '@components': path.resolve(__dirname, './src/components'),
            '@modules': path.resolve(__dirname, './src/modules'),
            '@pages': path.resolve(__dirname, './src/pages')
        }
    },
    server: {
        host: true,
        port: 3000,
        strictPort: true,
    },
    preview: {
        host: true,
        port: 3030,
        strictPort: true,
    }
});
