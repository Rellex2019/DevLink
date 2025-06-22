<template>
    <div :style="{ width: widthPer + 'vw' }" :class="['input-found-container', { focused: isFocus }], searchInput">
        <img v-if="svg" class="loop-svg" src="@/svg/loop.svg" alt="Лупа">
        <input class="input-search" @keydown.enter="handlePressEnter" @focus="onFocus" @input="handleChange" @blur="onBlur" v-model="inputContent"
            type="text" :placeholder="placeholderText" :style="!svg ? {paddingRight: '10px', paddingLeft: '10px'}:null">
        <div class="cont" @click="handleClickClear"><svg v-if="!empty" class="delete-svg"  viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1L6.5 6.5M12 12L6.5 6.5M6.5 6.5L1 12M6.5 6.5L12 1" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" />
        </svg>
    </div>
    </div>
</template>
<script>
import { ref } from 'vue';


export default {
    name: 'Search',
    data() {
        return {
            isFocus: false,
            widthPer: this.w / 19.2,
            inputContent: ref(''),
            empty: ref(true)
        };
    },
    emits:['focus-input', 'blur-input', 'write-input', 'clear-input', 'enter-input'],
    props: {
        searchInput: {
            required: false,
        },
        placeholderText: {
            type: String,
            required: false,
        },
        w: {
            required: false,
        },
        animated: {
            type: Boolean,
            required: false,
        },
        svg:{
            type: Boolean,
            default: true
        },
        content:{
            type: String,
            required: false
        }

    },
    watch:{
        content(newInfo, oldInfo)
        {
            this.inputContent = newInfo;
        }
    },
    methods: {
        onFocus() {
            this.$emit('focus-input');
            this.isFocus = true;

            if (this.animated && this.isFocus) {
                this.widthPer = this.widthPer * 2;
            }
        },
        onBlur() {
            this.$emit('blur-input');
            this.isFocus = false;

            if (this.animated) {
                this.widthPer = this.w / 19.2;
            }
        },
        handleChange() {
            this.$emit('write-input', this.inputContent);
            if (this.inputContent.length != 0) {
                this.empty = false;
            }
            else {
                this.empty = true;
            }
        },
        handleClickClear()
        {
            this.inputContent='';
            this.empty = true;
            this.$emit('write-input', this.inputContent);
            this.$emit('clear-input', this.inputContent);

        },
        handlePressEnter()
        {
            this.$emit('enter-input');
        }
    },

}
</script>

<style scoped>
.loop-svg {
    background: none;
    width: 13px;
    height: 13px;
    padding: 9px 7px 8px 9px;
}

.input-found-container {
    
    transition: 0.5s;
    display: flex;
    align-items: center;
    border: 1px solid #656A6F;
    background-color: #202123;
    border-radius: 5px;
}

.input-search {
    color: #F8F9FA;
    background: none;
    border: none;
    letter-spacing: 0.02em;
    height: 30px;
    width: 100%;
    font-size: 15px;
    font-family: 'Montserrat';
    text-overflow: ellipsis;
    padding-right: 9px;
}

.input-search:focus {
    outline-width: 0;
}

.input-found-container.focused {
    border-color: #EDB200;
}
.delete-svg{
    width: 10px;
    height: 10px;
    margin-right: 10px;
    color: #656A6F;
}
.delete-svg:hover{
    color: #EDB200;
}
</style>