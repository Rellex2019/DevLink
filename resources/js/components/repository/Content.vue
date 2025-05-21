<template>
  <div ref="editorContainer" class="code-editor"></div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from 'vue';
import loader from '@monaco-editor/loader';

const props = defineProps({
  content: String,
  language: {
    type: String,
    default: 'plaintext' 
  },
  id: Number
});

const emit = defineEmits(['update-content']);
const editorContainer = ref(null);
let editor = null;
let monacoInstance = null; 
let debounceTimer = null;
const DEBOUNCE_DELAY = 3000;
let currentId = ref(null);
let hasUnsavedChanges = ref(false);

const handleBeforeUnload = (e) => {
  if (hasUnsavedChanges.value) {
    e.preventDefault();
    e.returnValue = 'У вас есть несохраненные изменения. Вы уверены, что хотите уйти?';
    return e.returnValue;
  }
};

onMounted(async () => {
  window.addEventListener('beforeunload', handleBeforeUnload);

  loader.config({
    paths: {
      vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.36.1/min/vs'
    }
  });

  try {
    monacoInstance = await loader.init();

    editor = monacoInstance.editor.create(editorContainer.value, {
      value: props.content || '',
      language: getMonacoLanguage(props.language),
      theme: 'vs-dark',
      minimap: { enabled: true },
      fontSize: 18,
      automaticLayout: true,
      tabSize: 4
    });

    currentId.value = props.id;

    editor.onDidChangeModelContent(() => {
      hasUnsavedChanges.value = true;

      if (debounceTimer) {
        clearTimeout(debounceTimer);
      }

      debounceTimer = setTimeout(() => {
        saveContent(currentId.value);
        hasUnsavedChanges.value = false;
      }, DEBOUNCE_DELAY);
    });
  } catch (error) {
    console.error('Failed to initialize Monaco Editor:', error);
  }
});

function saveContent(id) {
  if (editor) {
    const value = editor.getValue();
    emit('update-content', value, id);
    if (debounceTimer) {
      clearTimeout(debounceTimer);
      debounceTimer = null;
    }
  }
}

watch(() => props.language, (newLang) => {
  if (editor && monacoInstance) {
    const model = editor.getModel();
    if (model) {
      try {
        monacoInstance.editor.setModelLanguage(model, getMonacoLanguage(newLang));
      } catch (e) {
        console.error('Ошибка в смене языка программирования:', e);
      }
    }
  }
});

watch(() => props.id, (newId, oldId) => {
  if (oldId && editor && hasUnsavedChanges.value) {
    saveContent(oldId);
    hasUnsavedChanges.value = false;
  }

  currentId.value = newId;
  if (editor) {
    editor.setValue(props.content || '');
  }
});

watch(() => props.content, (newVal) => {
  if (editor && newVal !== editor.getValue()) {
    editor.setValue(newVal);
  }
});

onUnmounted(() => {
  window.removeEventListener('beforeunload', handleBeforeUnload);

  if (debounceTimer) {
    clearTimeout(debounceTimer);
  }
  if (editor) {
    editor.dispose();
  }
});

function getMonacoLanguage(extension) {
  const map = {
    'js': 'javascript',
    'mjs': 'javascript',
    'cjs': 'javascript',
    'jsx': 'javascriptreact',
    'ts': 'typescript',
    'tsx': 'typescriptreact',
    'py': 'python',
    'pyw': 'python',
    'pyi': 'python',
    'java': 'java',
    'class': 'java',
    'jar': 'java',
    'cs': 'csharp',
    'go': 'go',
    'rs': 'rust',
    'rb': 'ruby',
    'php': 'php',
    'phtml': 'php',
    'php4': 'php',
    'php5': 'php',
    'phps': 'php',
    'kt': 'kotlin',
    'swift': 'swift',
    'scala': 'scala',
    'groovy': 'groovy',
    'dart': 'dart',
    'pl': 'perl',
    'pm': 'perl',
    'r': 'r',
    'jl': 'julia',
    'lua': 'lua',
    'hs': 'haskell',
    'erl': 'erlang',
    'ex': 'elixir',
    'exs': 'elixir',
    'clj': 'clojure',
    'cljs': 'clojure',
    'cljc': 'clojure',
    'edn': 'clojure',
    'sh': 'shell',
    'bash': 'shell',
    'zsh': 'shell',
    'fish': 'shell',
    'ps1': 'powershell',
    'psm1': 'powershell',
    'psd1': 'powershell',
    'c': 'c',
    'h': 'c',
    'cpp': 'cpp',
    'hpp': 'cpp',
    'cc': 'cpp',
    'hh': 'cpp',
    'cxx': 'cpp',
    'hxx': 'cpp',
    'm': 'objective-c',
    'mm': 'objective-cpp',

    // Веб-технологии
    'html': 'html',
    'htm': 'html',
    'xhtml': 'html',
    'css': 'css',
    'scss': 'scss',
    'sass': 'sass',
    'less': 'less',
    'styl': 'stylus',
    'json': 'json',
    'json5': 'json',
    'jsonc': 'jsonc',
    'xml': 'xml',
    'xsl': 'xml',
    'xslt': 'xml',
    'svg': 'xml',
    'yaml': 'yaml',
    'yml': 'yaml',
    'toml': 'toml',

    // Базы данных
    'sql': 'sql',
    'ddl': 'sql',
    'dml': 'sql',

    // Конфигурационные файлы
    'ini': 'ini',
    'cfg': 'ini',
    'conf': 'ini',
    'properties': 'properties',

    // Документация
    'md': 'markdown',
    'markdown': 'markdown',
    'rst': 'restructuredtext',

    // Сборка и развертывание
    'dockerfile': 'dockerfile',
    'dockerignore': 'ignore',
    'gitignore': 'ignore',
    'yml': 'yaml',
    'yaml': 'yaml',
    'makefile': 'makefile',
    'mk': 'makefile',

    // Тестирование
    'feature': 'gherkin',
    'spec': 'javascript', // или другой язык в зависимости от фреймворка

    // Шаблоны
    'ejs': 'html',
    'pug': 'jade',
    'jade': 'jade',
    'mustache': 'html',
    'hbs': 'handlebars',

    // Другие
    'txt': 'plaintext',
    'log': 'log',
    'csv': 'csv',
    'tsv': 'csv',
    'diff': 'diff',
    'patch': 'diff',
    'env': 'properties',

    // Специальные форматы
    'proto': 'protobuf',
    'graphql': 'graphql',
    'gql': 'graphql',
    'sol': 'solidity',
    'vy': 'vyper',

    // Ассемблеры
    'asm': 'asm6502',
    's': 'asm6502',
    'S': 'asm6502',

    // Функциональные языки
    'fs': 'fsharp',
    'fsi': 'fsharp',
    'fsx': 'fsharp',
    'ml': 'ocaml',
    'mli': 'ocaml',

    // Старые языки
    'vb': 'vb',
    'vbs': 'vbscript',
    'pl': 'perl',
    'pm': 'perl',

    // Новые/нишевые языки
    'zig': 'zig',
    'v': 'v',
    'nim': 'nim',
    'd': 'd',
    'odin': 'odin',

    // Дополнительные расширения
    'lock': 'json', // package-lock.json и подобные
    'editorconfig': 'ini',
    'babelrc': 'json',
    'eslintrc': 'json',
    'prettierrc': 'json',
    'stylelintrc': 'json'
  };
  return map[extension] || 'plaintext';
}
</script>

<style scoped>
.code-editor {
  width: 100%;
  height: 100%;
}
</style>