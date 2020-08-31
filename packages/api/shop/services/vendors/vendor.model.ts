import { DataTypes } from 'sequelize/types';
import sequelize from '../../../sequelize';

const Vendor = sequelize.define('Vendor', {
	id: {
		type: DataTypes.STRING,
		allowNull: false
	},
	slug: {
		type: DataTypes.STRING,
		allowNull: false
	},
	type: {
		type: DataTypes.STRING,
		allowNull: false
	},
	name: {
		type: DataTypes.STRING,
		allowNull: false
	},
	categories: {
		type: DataTypes.ARRAY(DataTypes.STRING),
		allowNull: true
	},
	logoUrl: {
		type: DataTypes.STRING,
		allowNull: true,
	},
	thumbnailUrl: {
		type: DataTypes.STRING,
		allowNull: false
	},
	previewUrl: {
		type: DataTypes.STRING,
		allowNull: false
	},
	slogan: {
		type: DataTypes.STRING,
		allowNull: true
	},
	description: {
		type: DataTypes.STRING,
		allowNull: false
	},
	address: {
		type: DataTypes.STRING,
		allowNull: true
	},
	promotion: {
		type: DataTypes.STRING,
		allowNull: true
	},
}, {
	timestamps: true,
});

export default Vendor;