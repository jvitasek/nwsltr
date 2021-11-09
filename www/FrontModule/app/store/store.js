import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        composer: {
            id: Number,
            recipientGroups: Array,
            meta: {
                author: String,
                date: Date,
                category: String
            },
            lastComponentId: Number,
            body: Array
        }
    },
    mutations: {
        setComposer(state, composer) {
            state.composer = composer
        },
        setComposerBody(state, composerBody) {
            state.composer.body = composerBody
        },
        setComposerRecipients(state, recipients) {
            state.composer.recipientGroups = recipients
        },
        pushComposerItem(state, item) {
            state.composer.body.push(item)
        },
        setComposerlastComponentId(state, lastComponentId) {
            state.composer.lastComponentId = lastComponentId
        },
        removeComposerItem(state, id) {
            state.composer.body = state.composer.body.filter(item => { return item.id !== id });
        },
        moveItemTop(state, item) {
            let oldIndex = state.composer.body.indexOf(item);
            let newIndex = oldIndex - 1;

            state.composer.body.splice(oldIndex, 1);
            state.composer.body.splice(newIndex, 0, item);
        },
        moveItemDown(state, item) {
            let oldIndex = state.composer.body.indexOf(item);
            let newIndex = oldIndex + 1;

            state.composer.body.splice(oldIndex, 1);
            state.composer.body.splice(newIndex, 0, item);
        },
        addListItem(state, payload) {
            state.composer.body.forEach(item => { 
                if(item.id == payload.componentId) {
                    item.items.splice(payload.index+1, 0, '')
                }
            })
        },
        removeListItem(state, payload) {
            state.composer.body.forEach(item => { 
                if(item.id == payload.componentId) {
                    item.items.splice(payload.index, 1)
                }
            })
        },
        updateMeta(state, payload) {
            state.composer.meta.title = payload.author
            state.composer.meta.subject = payload.date
            state.composer.meta.emailFrom = payload.emailFrom
            state.composer.meta.date = payload.category
        }
    },
    getters: {
        getComposer: state => state.composer,
        getComposerlastComponentId: state => state.composer.lastComponentId,
        getComponents: state => state.composer.body,
        getCampaignMeta: state => state.composer.meta
    }
})
