import "./bootstrap";

import {createApp} from "vue";
import Router from "./routes";
import {library} from "@fortawesome/fontawesome-svg-core";
import {faClose, faPlus, faTrash} from "@fortawesome/free-solid-svg-icons";

const app = createApp({}).use(Router).mount("#app");

library.add(faTrash, faPlus, faClose);
