
<template>
<transition name="fade">
  <div class="modal" :class="className" v-if="isVisible">
    <div class="modal-content">
      <slot></slot>
    </div>
  </div>
</transition>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    className:{
      type: String,
      required:false,
    },
    isVisible: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    close() {
      this.$emit('close');

    },
  },
};

</script>


<style scoped>

.fade-enter-active, .fade-leave-active {
    opacity: 0;
    transform: translateY(-15px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter, .fade-leave-to {
    opacity: 0; 
    transform: translateY(-15px);
}
.fade-enter-to, .fade-leave {
    opacity: 1; 
    transform: translateY(0px); 
}

.modal {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
    width: 100%;
  background: #161718;
  padding: 12px;
  border-radius: 5px;
  border: 1px solid #656A6F;
  position: relative;
}


</style>