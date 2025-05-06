CREATE TABLE IF NOT EXISTS country (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) UNIQUE,
  continent VARCHAR(50),
  population INTEGER,
  capital VARCHAR(50),
  currency VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS city (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50),
  country_id INTEGER,
  district VARCHAR(50),
  state VARCHAR(50),
  CONSTRAINT FK_city_country FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS weather (
  id SERIAL PRIMARY KEY,
  city_id INTEGER,
  weather VARCHAR(50),
  temp_max FLOAT, -- saved in kelvin
  temp_min FLOAT, -- saved in kelvin
  date DATE,
  description VARCHAR(50),
  CONSTRAINT FK_weather_city FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE ON UPDATE CASCADE
);
