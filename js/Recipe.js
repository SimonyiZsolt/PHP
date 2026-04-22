class Recipe {
    title;
    ingredientsAndQuantities;
    description;
    imageURL;

    constructor(title, ingredientsAndQuantities, description, imageURL) {
        this.title = title;
        this.ingredientsAndQuantities = ingredientsAndQuantities;
        this.description = description;
        this.imageURL = imageURL;
    }
}