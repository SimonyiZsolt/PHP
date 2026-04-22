class IngredientAndQuantity {
    ingredient;
    quantity;
    mealType;

    constructor(ingredient, quantity, mealType = "") {
        this.ingredient = ingredient;
        this.quantity = quantity;
        this.mealType = mealType;
    }

    setMealType(typeString) {
        this.mealType = typeString;
    }
}