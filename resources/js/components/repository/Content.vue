<template>
    <div  ref="editorContainer" class="code-editor"></div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import * as monaco from 'monaco-editor';
  
  const props = defineProps({
    content: String,
    language: {
      type: String,
      default: 'html' 
    },
    id: Number
  });
  
  const emit = defineEmits(['update-content']);
  const editorContainer = ref(null);
  let editor = null;
  
  onMounted(() => {
    editor = monaco.editor.create(editorContainer.value, {
      value: props.content || '',
      language: props.language,
      theme: 'vs-dark', 
      minimap: { enabled: true },
      fontSize: 14,
      automaticLayout: true,
      tabSize: 4  
    });
  
    editor.onDidChangeModelContent(() => {
      const value = editor.getValue();
      emit('update-content', value, props.id);
    });
  });
  
  watch(() => props.content, (newVal) => {
    if (editor && newVal !== editor.getValue()) {
      editor.setValue(newVal);
    }
  });
  </script>
  
  <style scoped>
  .code-editor {
    width: 100%;
    height: 100%;
  }
  </style>