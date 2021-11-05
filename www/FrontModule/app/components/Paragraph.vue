<template>
    <div class="composer__item" :id="component.id">
        <div class="center-wrapper">
            <div class="composer__tools">
                <button v-on:click="setBold(component.id)">
                    <svg class="icon icon--sm">
                        <title>Bold</title>
                        <use xlink:href="#bold"></use>
                    </svg>
                </button>
                <button v-on:click="setItalic(component.id)">
                    <svg class="icon icon--sm">
                        <title>Italic</title>
                        <use xlink:href="#italic"></use>
                    </svg>
                </button>
                <button v-on:click="setUnderline(component.id)">
                    <svg class="icon icon--sm">
                        <title>Underline</title>
                        <use xlink:href="#underline"></use>
                    </svg>
                </button>
                <button v-on:click="handleRemove(component.id)">
                    <svg class="icon">
                        <title>Remove</title>
                        <use xlink:href="#trash"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemTop(component)">
                    <svg class="icon">
                        <title>Move up</title>
                        <use xlink:href="#atop"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemDown(component)">
                    <svg class="icon">
                        <title>Move down</title>
                        <use xlink:href="#adown"></use>
                    </svg>
                </button>
            </div>
            <div contenteditable="true"
                :class="component.paragraphType === 'perex' ? 'input paragraph paragraph-perex' : 'input paragraph editable'"
                :id="'input-'+component.id"
                @keydown="handleKeyDown"
                @paste="handlePaste"
                @input="handleInput"
            >
                {{ component.content }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            component: Object
        },
        name: "Paragraph",
        methods: {
            handleRemove(id) {
                if(this.component.required) {
                    this.$toasted.error('Cannot remove a required field')
                }
                else {
                    this.$store.commit('removeComposerItem', id)
                }
            },
            moveItemTop(item) {
                if(this.component.required) {
                    this.$toasted.error('Cannot move a required field')
                }
                else {
                    this.$store.commit('moveItemTop', item, 1)
                }
            },
            moveItemDown(item) {
                if(this.component.required) {
                    this.$toasted.error('Cannot move a required field')
                }
                else {
                    this.$store.commit('moveItemDown', item, 1)
                }
            },
            addParagraph(paragraphType, paragraphContent) {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Paragraph',
                    paragraphType: paragraphType,
                    content: paragraphContent,
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => { 
                    document.querySelector('#input-'+newItem.id).focus()
                    document.querySelector('#input-'+newItem.id).value = ''
                });
            },
            handleKeyDown(e) {
                if(e.key === 'Enter') {
                    e.preventDefault();
                    this.addParagraph('text')
                }
            },
            handlePaste(e) {
                e.preventDefault()
                let clipboardText = e.clipboardData.getData('text').split(/\r?\n/)
                this.component.content = clipboardText[0]

                for(let i = 1; i <= clipboardText.length; i++) {
                    this.addParagraph('text', clipboardText[i])
                }
            },
            handleInput(e) {
                this.component.content = e.target.innerHTML
            },
            setBold() {
                document.execCommand('bold')
            },
            setItalic() {
                document.execCommand('italic')
            },
            setUnderline() {
                document.execCommand('underline')
            }
        },
        mounted() {
            document.querySelectorAll('.paragraph').forEach(function (item) {
                item.addEventListener('focus', () => {
                    item.innerHTML = item.innerHTML.replace('<p>', '').replace('</p>', ''); 
                });
            });
        }
    };
</script>