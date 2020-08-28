import { registerBlockType } from "@wordpress/blocks";

registerBlockType(
  "pg/basic", 
  {
  title: "Basic Block",
  description: "Este es nuestro primer bloque",
  icon: "smiley",
  category: "layout",
  edit: () => <h2>Helo World</h2>,
  save: () => <h2>Helo World</h2>,
  }
);
