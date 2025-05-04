<template>
  <div class="elipsis-container">
    <div class="elipsis" @click.stop="toggleModal">
      <div class="point"></div>
      <div class="point"></div>
      <div class="point"></div>
    </div>

    <!-- Модальное окно рендерится в body -->
    <teleport to="body">
      <transition name="dropdown">
        <div v-if="enableModal" class="global-modal-overlay" @click.self="closeModal">
          <div class="context-menu" :style="menuPosition" @click.stop>
            <div class="option" @click="handleEdit">
              Изменить
            </div>
            <div class="option" @click="handleDelete">
              Удалить
            </div>
          </div>
        </div>
      </transition>
    </teleport>
  </div>
</template>

<script>
export default {
  name: 'Elipsis',
  props: {
    id: {
      required: true
    },
    // Дополнительные параметры позиционирования
    positionRelativeTo: {
      type: HTMLElement,
      default: null
    }
  },
  emits: ['delete', 'edit'],
  inject: ['deleteTask'],
  data() {
    return {
      enableModal: false,
      menuPosition: {
        top: '0px',
        left: '0px'
      }
    }
  },
  methods: {
    toggleModal(e) {
      this.enableModal = !this.enableModal;
      if (this.enableModal) {
        this.calculatePosition(e);
      }
    },
    calculatePosition(clickEvent) {
      this.$nextTick(() => {
        const triggerElement = clickEvent?.target.closest('.elipsis-container') || this.positionRelativeTo;
        if (!triggerElement) return;

        const rect = triggerElement.getBoundingClientRect();
        this.menuPosition = {
          top: `${rect.bottom + window.scrollY}px`,
          left: `${rect.right + window.scrollX - 150}px` // 150 - ширина меню
        };
      });
    },
    closeModal() {
      this.enableModal = false;
    },
    handleDelete() {
      this.deleteTask(this.id)
      this.closeModal();
    },
    handleEdit() {
      this.$emit('edit', this.id);
      this.closeModal();
    }
  },
  mounted() {
    document.addEventListener('click', this.closeModal);
    document.addEventListener('scroll', this.closeModal, true);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.closeModal);
    document.removeEventListener('scroll', this.closeModal, true);
  }
}
</script>

<style scoped>
.elipsis-container {

  position: relative;
  display: inline-block;
  z-index: 1;
}

.elipsis {
  cursor: pointer;
  display: flex;
  gap: 3.5px;
  padding: 5px;
  transition: transform 0.2s;
}

.elipsis:hover {
  transform: scale(1.1);
}

.point {
  width: 4px;
  height: 4px;
  border-radius: 2px;
  background-color: #7B7B7B;
  transition: background-color 0.2s;
}

.elipsis:hover .point {
  background-color: #F8F9FA;
}

/* Стили для модального окна в body */
.global-modal-overlay {
  font-family: 'Montserrat';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
  /* background: rgba(0, 0, 0, 0.3); */
  pointer-events: auto;
}

.context-menu {
  position: absolute;
  background: #343A40;
  border: 1px solid #161718;
  border-radius: 8px;
  min-width: 150px;
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
  z-index: 10000;
}

.option {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  cursor: pointer;
  color: #F8F9FA;
  transition: background-color 0.2s;
}

.option:hover {
  background-color: #161718;
}

/* Анимации */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.25s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>