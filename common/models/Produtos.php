<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "produtos".
 *
 * @property int $id
 * @property int|null $id_categoria
 * @property string|null $nome
 * @property string|null $descricao
 * @property float|null $preco
 * @property int|null $stock
 * @property string|null $imagem
 * @property string|null $marca
 * @property string|null $tamanho
 * @property string|null $cores
 *
 * @property Avaliacoes[] $avaliacoes
 * @property CarrinhoLinhas[] $carrinhoLinhas
 * @property Categorias $categoria
 * @property FaturaLinhas[] $faturaLinhas
 * @property Favoritos[] $favoritos
 * @property Promocoes[] $promocoes
 */
class Produtos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produtos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_categoria', 'stock'], 'integer'],
            [['preco'], 'number'],
            [['imagem', 'tamanho', 'cores'], 'string'],
            [['nome', 'marca'], 'string', 'max' => 45],
            [['descricao'], 'string', 'max' => 255],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::class, 'targetAttribute' => ['id_categoria' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_categoria' => 'Categoria',
            'nome' => 'Nome',
            'descricao' => 'Descricao',
            'preco' => 'Preco',
            'stock' => 'Stock',
            'imagem' => 'Imagem',
            'marca' => 'Marca',
            'tamanho' => 'Tamanho',
            'cores' => 'Cores',
        ];
    }

    /**
     * Gets query for [[Avaliacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacoes()
    {
        return $this->hasMany(Avaliacoes::class, ['id_produto' => 'id']);
    }

    /**
     * Gets query for [[CarrinhoLinhas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhoLinhas()
    {
        return $this->hasMany(CarrinhoLinhas::class, ['id_produto' => 'id']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categorias::class, ['id' => 'id_categoria']);
    }

    /**
     * Gets query for [[FaturaLinhas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturaLinhas()
    {
        return $this->hasMany(FaturaLinhas::class, ['id_produto' => 'id']);
    }

    /**
     * Gets query for [[Favoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritos()
    {
        return $this->hasMany(Favoritos::class, ['id_produto' => 'id']);
    }

    /**
     * Gets query for [[Promocoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPromocoes()
    {
        return $this->hasMany(Promocoes::class, ['id_produto' => 'id']);
    }
}
