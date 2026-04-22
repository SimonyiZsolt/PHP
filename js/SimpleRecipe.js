class SimpleRecipe {
    id;
    title;
    calories;
    protein;
    carbohydrate;
    fat;
    imageURL;

    constructor(id, title, calories, protein, carbohydrate, fat, imageURL) {
        this.title = title;
        this.calories = calories;
        this.protein = protein;
        this.carbohydrate = carbohydrate;
        this.fat = fat;
        this.imageURL = imageURL;
    }
}