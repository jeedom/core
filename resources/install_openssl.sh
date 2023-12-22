VERSION=3.2.0
apt update
apt install -y build-essential checkinstall zlib1g-dev
cd /usr/local/src
wget https://www.openssl.org/source/openssl-${VERSION}.tar.gz
tar -xf openssl-${VERSION}.tar.gz
cd openssl-${VERSION}/
./config --prefix=/usr/local/ssl --openssldir=/usr/local/ssl shared zlib
make -j$(nproc)
make install
rm /etc/ld.so.conf.d/openssl-*.conf
echo "/usr/local/ssl/lib64" > /etc/ld.so.conf.d/openssl-${VERSION}.conf
ldconfig -v
mv /usr/bin/c_rehash /usr/bin/c_rehash.bak
mv /usr/bin/openssl /usr/bin/openssl.bak
mv /usr/local/ssl/bin/c_rehash /usr/bin/
mv /usr/local/ssl/bin/openssl /usr/bin/
openssl version
rm -rf /usr/local/src/openssl-${VERSION}.tar.gz
rm -rf /usr/local/src/openssl-${VERSION}
