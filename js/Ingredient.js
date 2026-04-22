class Ingredient {
    id;
    name;
    unit;
    calories;
    protein;
    carbohydrate;
    fat;

    constructor(id, name, unit, calories, protein, carbohydrate, fat) {
        this.id = id;
        this.name = name;
        this.unit = unit;
        this.calories = calories;
        this.protein = protein;
        this.carbohydrate = carbohydrate;
        this.fat = fat;
    }

    static build(obj){
        return new Ingredient(obj.id, obj.name, obj.unit, obj.calories, obj.protein, obj.carbohydrate, obj.fat);
    }
}