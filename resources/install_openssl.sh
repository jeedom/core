apt update
apt install -y build-essential checkinstall zlib1g-dev
cd /usr/local/src
wget https://www.openssl.org/source/openssl-3.1.4.tar.gz
tar -xf openssl-3.1.4.tar.gz
cd openssl-3.1.4/
./config --prefix=/usr/local/ssl --openssldir=/usr/local/ssl shared zlib
make -j$(nproc)
make install
echo "/usr/local/ssl/lib64" > /etc/ld.so.conf.d/openssl-3.1.4.conf
ldconfig -v
mv /usr/bin/c_rehash /usr/bin/c_rehash.bak
mv /usr/bin/openssl /usr/bin/openssl.bak
mv /usr/local/ssl/bin/c_rehash /usr/bin/
mv /usr/local/ssl/bin/openssl /usr/bin/
openssl version
rm -rf /usr/local/src/openssl-3.1.4.tar.gz
rm -rf /usr/local/src/openssl-3.1.4
